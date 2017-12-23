
function $(selector, context){
    return new mQuery(selector, context);
}


class mQuery extends Array{

    constructor(selector, context){
        super();
        this.context = (context instanceof Document) ? context : document;
        for(const v of this.$toBox(selector)){
            if(v != null){
                this.push(v);
            }
        }
    }


    $chain(set){
        return new mQuery(set, this.context);
    }


    $toBox(content){
        const type = $.type(content);

        if(type === 'string'){
            try{
                content = this.context.querySelectorAll(content.trim());
            }
            catch(e){
                content = this.$parseHTML(content.trim());
            }
        }
        else if(type === 'array' || content instanceof NodeList || content instanceof HTMLCollection || content instanceof Set){
            //content = content
        }
        else if(type === 'function'){ //モジュールはDOM構築後に実行されるので下記は常に偽となる
            this.context.readyState === 'loading' ? this.context.addEventListener('DOMContentLoaded', content) : content();
            content = [];
        }
        else if(type === 'null' || type === 'undefined'){
            content = [];
        }
        else{ //HTMLElement, Document, ...
            content = [content];
        }

        return content;
    }


    $parseHTML(html){
        const doc = (new DOMParser).parseFromString(html, 'text/html');
        const box = [];
        for(const v of [...doc.head.children, ...doc.body.childNodes]){
            if(v == null){
                continue;
            }
            box.push(document.importNode(v, true));
        }
        return box;
    }


    $each(func){
        for(const v of this){
            if(v.nodeType !== 1){
                continue;
            }
            func(v);
        }
        return this;
    }


    $exists(prop){
        return this.length && (prop in this[0]);
    }


    first(){
        return this.eq(0);
    }


    last(){
        return this.eq(-1);
    }


    eq(i = 0){
        if(i < 0){
            i = this.length + i;
        }
        return this.$chain([this[i]]);
    }


    and(selector){
        return this.$chain(this.filter(v => ('matches' in v) && v.matches(selector)));
    }


    not(selector){
        return this.$chain(this.filter(v => ('matches' in v) && !v.matches(selector)));
    }


    is(selector){
        return this.some(v => ('matches' in v) && v.matches(selector));
    }


    add(selector){
        const add = this.concat(...this.$toBox(selector));
        return this.$chain($.unique(add));
    }


    index(){
        return this.$exists('parentNode') ? Array.from(this[0].parentNode.children).indexOf(this[0]) : -1;
    }


    log(...args){
        console.log(this);
        args.forEach(v => console.log(v));
        return this;
    }


    dump(){
        return this.context.documentElement.outerHTML; //document.doctypeは取得していない
    }


    text(str){
        if(str === undefined){ //本文を取得
            return this.$exists('textContent') ? this[0].textContent : undefined;
        }
        //本文を設定
        this.forEach(v => v.textContent = str);
        return this;
    }


    html(str){
        if(str === undefined){ //本文を取得(自身を含まない)
            return this.$exists('innerHTML') ? this[0].innerHTML : undefined;
        }
        else if(str === true){ //本文を取得(自身を含む)
            return this.$exists('outerHTML') ? this[0].outerHTML : undefined;
        }
        //本文を設定
        return this.$each(v => v.innerHTML = str);
    }


    empty(){
        this.forEach(v => v.textContent = '');
        return this;
    }


    name(){
        return this.$exists('tagName') ? this[0].tagName.toLowerCase() : undefined;
    }


    move(selector, position){
        const content = this.$manipulate('remove');
        return this.$chain(this.$manipulate(position, content, this.$toBox(selector)));
    }


    moved(content, position){
        content = this.$manipulate('remove', null, this.$toBox(content));
        return this.$chain(this.$manipulate(position, content));
    }


    paste(selector, position){
        return this.$chain(this.$manipulate(position, this, this.$toBox(selector)));
    }


    pasted(content, position){
        return this.$chain(this.$manipulate(position, this.$toBox(content)));
    }


    replace(selector){
        return this.$chain(this.$manipulate('replace', this, this.$toBox(selector)));
    }


    replaced(content){
        return this.$chain(this.$manipulate('replace', this.$toBox(content)));
    }


    copy(){
        return this.$chain(this.$manipulate('clone'));
    }


    remove(){
        return this.$chain(this.$manipulate('remove'));
    }


    $manipulate(mode = 'replace', content = [], target = this){
        const selection = [];

        mode = String(mode).toLowerCase();

        if(mode === 'clone'){
            for(const position of target){
                selection.push( position.cloneNode(true) );
            }
        }
        else if(mode === 'remove'){
            for(const position of target){
                selection.push( position.parentNode.removeChild(position) );
            }
        }
        else if(mode === 'prev' || mode === '1'){
            for(const position of target){
                for(let i = 0; i < content.length; i++){
                    selection.push( position.parentNode.insertBefore(content[i].cloneNode(true), position) );
                }
            }
        }
        else if(mode === 'firstchild' || mode === '-1'){
            for(const position of Array.from(target).reverse()){
                for(let i = content.length - 1; i >= 0; i--){
                    selection.unshift( position.insertBefore(content[i].cloneNode(true), position.firstChild) );
                }
            }
        }
        else if(mode === 'lastchild' || mode === '-2'){
            for(const position of target){
                for(let i = 0; i < content.length; i++){
                    selection.push( position.appendChild(content[i].cloneNode(true)) );
                }
            }
        }
        else if(mode === 'next' || mode === '2'){
            for(const position of Array.from(target).reverse()){
                for(let i = content.length - 1; i >= 0; i--){
                    selection.unshift( position.parentNode.insertBefore(content[i].cloneNode(true), position.nextSibling) );
                }
            }
        }
        else{ // replace
            for(const position of target){
                for(let i = 0; i < content.length; i++){
                    selection.push( position.parentNode.replaceChild(content[i].cloneNode(true), position) );
                }
            }
        }

        return selection;
    }


    attr(name, value){
        if(value !== undefined){ //属性を1つ設定
            return this.$each(v => v.setAttribute(name, value));
        }
        else if($.type(name) === 'string'){ //属性を1つ取得
            return this.$exists('getAttribute') ? this[0].getAttribute(name) : undefined;
        }
        else if($.type(name) === 'object'){ //属性を複数設定(再帰)
            Object.keys(name).forEach(k => this.attr(k, name[k]));
            return this;
        }
        //属性を全て返す
        const result = {};
        const attrs  = this.$exists('attributes') ? this[0].attributes : [];
        for(let i = 0; i < attrs.length; i++){
            result[attrs.item(i).name] = attrs.item(i).value;
        }
        return result;
    }


    removeAttr(name){
        if(name !== undefined){ //属性を1つ削除
            return this.$each(v => v.removeAttribute(name));
        }
        //属性を全て削除
        for(const element of this){
            const attrs = element.attributes || [];
            for(let i = attrs.length - 1;  i >= 0;  i--){
                element.removeAttribute(attrs.item(i).name);
            }
        }

        return this;
    }


    css(name, value){
        if(value !== undefined){ //CSSを1つ設定
            name = this.$toChainCase(name);
            return this.$each(v => v.style.setProperty(name, value));
        }
        else if($.type(name) === 'string'){ //CSSを1つ取得
            name = this.$toChainCase(name);
            return this.$exists('style') ? window.getComputedStyle(this[0])[name] : undefined;
        }
        else if($.type(name) === 'object'){ //CSSを複数設定(再帰)
            Object.keys(name).forEach(k => this.css(k, name[k]));
        }

        return this;
    }


    removeCSS(name){
        return this.$each(v => v.style.removeProperty(name));
    }


    addClass(name){
        return this.$each(v => v.classList.add(name));
    }


    removeClass(name){
        return this.$each(v => v.classList.remove(name));
    }


    toggleClass(name){
        return this.$each(v => v.classList.toggle(name));
    }


    hasClass(name){
        return this.some(v => ('classList' in v) && v.classList.contains(name));
    }


    prop(name, value){
        if(value !== undefined){ //プロパティを設定
            this.forEach(v => v[name] = value);
            return this;
        }
        //プロパティを取得
        return this.length ? this[0][name] : undefined;
    }


    removeProp(name){
        this.forEach(v => delete v[name]);
        return this;
    }


    val(value){
        return this.prop('value', value);
    }


    data(content){
        if(content !== undefined){ //データを保存
            return this.$each(v => v.dataset.mquery = JSON.stringify(content));
        }
        //データを取得
        try{
            return this.$exists('dataset') ? JSON.parse(this[0].dataset.mquery) : undefined;
        }
        catch(e){
            return this[0].dataset.mquery;
        }
    }


    removeData(){
        return this.$each(v => delete v.dataset.mquery);
    }


    geo(more){
        if(!this.$exists('getBoundingClientRect')){
            return {};
        }
        const rect = this[0].getBoundingClientRect();
        rect.x += rect.scrollX = window.scrollX;
        rect.y += rect.scrollY = window.scrollY;

        if(more){
            const paddingWidth  = this.$gcs('padding-left') + this.$gcs('padding-right');
            const paddingHeight = this.$gcs('padding-top')  + this.$gcs('padding-bottom');
            const marginWidth   = this.$gcs('margin-left')  + this.$gcs('margin-right')
            const marginHeight  = this.$gcs('margin-top')   + this.$gcs('margin-bottom')

            rect.innerWidth  = this[0].clientWidth  - paddingWidth;
            rect.innerHeight = this[0].clientHeight - paddingHeight;
            rect.InnerWidth  = this[0].clientWidth;
            rect.InnerHeight = this[0].clientHeight;
            rect.outerWidth  = rect.width  + marginWidth;
            rect.outerHeight = rect.height + marginHeight;
        }
        return rect;
    }


    $gcs(prop){
        return window.parseInt(window.getComputedStyle(this[0])[prop], 10) || 0;
    }


    find(selector){
        return this.$setCollection(v => v.querySelectorAll(selector));
    }


    firstChild(selector){
        return this.$setCollection(v => this.$traverseFamily(v, 'firstElementChild', selector, true));
    }


    lastChild(selector){
        return this.$setCollection(v => this.$traverseFamily(v, 'lastElementChild', selector, true));
    }


    children(selector){
        return this.$setCollection(v => this.$traverseChild(v, 'children', selector));
    }


    contents(selector){
        return this.$setCollection(v => this.$traverseChild(v, 'childNodes', selector));
    }


    isEmpty(){
        return this.every(v => ('childNodes' in v) && !v.childNodes.length);
    }


    parent(selector){
        return this.$setCollection(v => this.$traverseFamily(v, 'parentNode', selector, true));
    }


    parents(selector){
        return this.$setCollection(v => this.$traverseFamily(v, 'parentNode', selector));
    }


    parentsUntil(selector){
        return this.$setCollection(v => this.$traverseFamilyUntil(v, 'parentNode', selector));
    }


    closest(selector){
        return selector ? this.$setCollection(v => [v.closest(selector)]) : this.parent();
    }


    root(){
        return this.$chain([this.context.documentElement]);
    }


   offsetParent(){
        return this.$setCollection(v => [v.offsetParent]);
    }


    prev(selector){
        return this.$setCollection(v => this.$traverseFamily(v, 'previousElementSibling', selector, true));
    }


    prevAll(selector){
        return this.$setCollection(v => this.$traverseFamily(v, 'previousElementSibling', selector));
    }


    prevUntil(selector){
        return this.$setCollection(v => this.$traverseFamilyUntil(v, 'previousElementSibling', selector));
    }


    next(selector){
        return this.$setCollection(v => this.$traverseFamily(v, 'nextElementSibling', selector, true));
    }


    nextAll(selector){
        return this.$setCollection(v => this.$traverseFamily(v, 'nextElementSibling', selector));
    }


    nextUntil(selector){
        return this.$setCollection(v => this.$traverseFamilyUntil(v, 'nextElementSibling', selector));
    }


    siblings(selector){
        return this.$setCollection(v => [...this.$traverseChild(v.parentNode, 'children', selector)].filter(v2 => v2 !== v));
    }


    * $traverseFamily(startElement, prop, selector, alone){
        for(let v = startElement[prop]; v; v = v[prop]){
            if(v.nodeType !== 1){
                continue;
            }
            if(!selector || v.matches(selector)){
                yield v;
            }
            if(alone){
                break;
            }
        }
    }


    * $traverseFamilyUntil(startElement, prop, selector){ //jQは指定セレクタ手前まで選択で動作が違う
        for(let v = startElement[prop]; v; v = v[prop]){
            if(v.nodeType !== 1){
                continue;
            }
            yield v;
            if(selector && v.matches(selector)){
                break;
            }
        }
    }


    * $traverseChild(parentElement, prop, selector){
        for(const child of parentElement[prop]){
            if(prop === 'childNodes' && child.nodeType !== 1){
                yield child;
            }
            else if(!selector || child.matches(selector)){
                yield child;
            }
        }
    }


    $setCollection(func){
        const set = new Set();
        for(const element of this){
            if(element.nodeType !== 1 && element.nodeType !== 9){ // 9 === document
                continue;
            }
            for(const v of func(element)){
                set.add(v);
            }
        }
        return this.$chain(set);
    }


    on(name, option, handler){
        if(name.match(/\s/)){
            name.trim().split(/\s+/).forEach(v => this.on(v, option, handler));
            return this;
        }

        if(handler === undefined){
            handler = option;
            option  = {};
        }

        name = name.toLowerCase();

        const args = [];
        args[0] = name.replace(/\..*/, '');
        args[1] = function(e){
            if(option.find && !e.target.matches(option.find)){ // デリゲート
                return;
            }
            if('name' in e && e.name.includes('.') && e.name !== name){ // trigger()から呼ばれて名前付きの場合
                return;
            }
            e.data = ('data' in e) ? e.data : option.data;
            e.name = ('name' in e) ? e.name : name;
            e.this = e.target;
            if(handler.call(this, e) === false){
                e.stopPropagation();
                e.preventDefault();
            }
        };
        args[2] = {once: Boolean(option.once), passive: Boolean(option.passive), capture: Boolean(option.capture)};

        for(const v of this){
            if(!('addEventListener' in v)){
                continue;
            }
            v.mqEvent = ('mqEvent' in v) ? v.mqEvent : {};
            if(name in v.mqEvent && name.includes('.')){ //名前付きは重複登録禁止
                throw `mQuery.on('${name}') event is already registered.`;
            }
            v.mqEvent[name] = args;
            v.addEventListener(...args);
        }
        return this;
    }


    once(name, option, handler){
        if(handler === undefined){
            handler = option;
            option  = {};
        }
        option.once = true;
        return this.on(name, option, handler);
    }


    off(name){
        name = name.toLowerCase();

        for(const v of this){
            if(!('removeEventListener' in v)){
                continue;
            }
            v.removeEventListener(...(v.mqEvent[name]));
            delete v.mqEvent[name];
        }
        return this;
    }


    trigger(name, option = {}){
        name = name.toLowerCase();

        const bubbles    = ('bubbles'    in option) ? option.bubbles    : true;
        const cancelable = ('cancelable' in option) ? option.cancelable : true;
        const composed   = ('composed'   in option) ? option.composed   : false;
        delete option.bubbles;
        delete option.cancelable;
        delete option.composed;

        for(const v of this){
            if(!('dispatchEvent' in v)){
                continue;
            }
            const event = new Event(name.replace(/\..*/, ''), {bubbles, cancelable, composed});
            event.name = name;
            v.dispatchEvent(Object.assign(event, option));
        }
        return this;
    }


    click(){
        return this.$each(v => v.click());
    }


    serialize(addParam = {}){
        if(this.name() !== 'form'){
            return;
        }
        const param = new URLSearchParams();
        for(const [k, v] of new FormData(this[0])){
            param.append(k, v); //同一名がありえるのでsetではない
        }
        Object.keys(addParam).forEach(k => param.append(k, addParam[k]));
        return param; //空白は+になる
    }


    request(addParam = {}){
        if(this.name() !== 'form'){
            return;
        }
        const headers = new Headers({'X-Requested-With':'XMLHttpRequest'});
        const method  = this[0].method  || 'GET';
        const enctype = this[0].enctype || 'application/x-www-form-urlencoded';
        const action  = this[0].action  || '';
        let   url     = action.replace(/#.*/, '') || location.href;
        let   body;

        const param = enctype.match(/multipart/i)  ?  new FormData(this[0])  :  this.serialize();
        Object.keys(addParam).forEach(k => param.append(k, addParam[k]));

        if(method.match(/(GET|HEAD)/i)){
            url = url.replace(/\?.*/, '') + '?' + param;
        }
        else{
            body = param;
            headers.set('Content-Type', enctype);
        }

        return new Request(url, {method, body, headers, credentials:'include'});
    }


    show(){
        return this.$each(v => this.$show(v));
    }


    hide(){
        return this.$each(v => this.$hide(v));
    }


    toggle(){
        return this.$each(v => v.style.display === 'none' ? this.$show(v) : this.$hide(v));
    }


    $show(v){
        if(v.style.display === 'none'){
            v.style.display = v.mqDisplay || '';
        }
    }


    $hide(v){
        if(v.style.display !== 'none'){
            v.mqDisplay = window.getComputedStyle(v)['display'];
            v.style.display = 'none';
        }
    }


    $toCamelCase(str = ''){
        return str.replace(/-([a-z])/g, m => `${m[1].toUpperCase()}`);
    }


    $toChainCase(str = ''){
        return str.replace(/([a-z])([A-Z])/g, m => `${m[0]}-${m[1].toLowerCase()}`);
    }
}




$.tag = function(tagName){
    const dom = document.createElement(tagName);
    return new mQuery(dom);
};


$.style = function(css){
    const style = document.createElement('style');
    style.innerHTML = css;
    return new mQuery(style);
};


$.script = function(queue, callback){
    if(!Array.isArray(queue)){
        queue = [queue];
    }
    if(!queue.length){
        if(typeof callback === 'function'){
            callback();
        }
        return;
    }
    const script = document.createElement('script');
    let   url    = queue.shift();

    if(url.match(/^module:/i)){
        url = url.replace(/^module:/i, '');
        script.type = 'module';
    }

    script.onload  = () => $.script(queue, callback);
    script.src     = url;
    document.body.appendChild(script);
};


$.html = function(html = ''){
    const doc = (new DOMParser).parseFromString(html.trim(), 'text/html');
    return new mQuery(doc.documentElement, doc)
};


$.xml = function(xml = ''){
    const doc = (new DOMParser).parseFromString(xml.trim(), 'application/xml');
    return new mQuery(doc.documentElement, doc)
};


$.geo = function(){
    const html = document.documentElement.getBoundingClientRect();
    return {
        htmlWidth     : html.width,
        htmlHeight    : html.height,
        browserWidth  : document.documentElement.clientWidth, //スクロールバーを含まない
        browserHeight : document.documentElement.clientHeight,
        BrowserWidth  : window.innerWidth, //スクロールバーを含む
        BrowserHeight : window.innerHeight,
        monitorWidth  : screen.width,
        monitorHeight : screen.height,
        scrollX       : window.scrollX,
        scrollY       : window.scrollY,
    };
};


$.sleep = function(sec){
    return new Promise(ok => setTimeout(ok, sec*1000));
};


$.unique = function(array = []){
    return array.filter((v, k) => array.indexOf(v) === k);
};


$.type = function(x){
    return Object.prototype.toString.call(x).slice(8, -1).toLowerCase();
};


$.fn = mQuery.prototype;


export default $;
export {mQuery};
