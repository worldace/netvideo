
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
                content = Array.from(this.context.querySelectorAll(content.trim()));
            }
            catch(e){
                content = this.$parseHTML(content);
            }
        }
        else if(type === 'nodelist' || type === 'htmlcollection' || type === 'set'){
            content = Array.from(content);
        }
        else if(type === 'array'){
            if(!(content instanceof mQuery)){
                content = content.filter(v => v instanceof Node);
            }
        }
        else if(type === 'window' || type === 'htmldocument' || content instanceof Element){
            content = [content];
        }
        else if(type === 'function'){
            this.context.readyState === 'loading' ? this.context.addEventListener('DOMContentLoaded', content) : content(); //モジュールなので常に偽となる
            content = [];
        }
        else{
            content =  [];
        }

        return content;
    }


    $parseHTML(html){
        const doc = (new DOMParser).parseFromString(html, 'text/html');
        return [...doc.head.children, ...doc.body.childNodes].filter(v => v != null);
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
        return this.$exists('parentElement') ? Array.from(this[0].parentElement.children).indexOf(this[0]) : -1;
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
        return this.$manipulate(position, this.$toBox(selector), this);
    }


    moved(content, position){
        return this.$manipulate(position, this, this.$toBox(content));
    }


    paste(selector, position){
        return this.$manipulate(position, this.$toBox(selector), this, true);
    }


    pasted(content, position){
        return this.$manipulate(position, this, this.$toBox(content), true);
    }


    replace(selector){
        return this.$manipulate('replace', this.$toBox(selector), this);
    }


    replaced(content){
        return this.$manipulate('replace', this, this.$toBox(content));
    }


    $manipulate(mode, refs, adds, copy = false){
        mode = String(mode).toLowerCase();
        let reversed = false;

        if(mode === 'prev' || mode === '1'){
            mode = this.$manipulate_prev;
        }
        else if(mode === 'next' || mode === '2'){
            mode = this.$manipulate_next;
            adds.reverse();
            reversed = true;
        }
        else if(mode === 'firstchild' || mode === '-1'){
            mode = this.$manipulate_firstchild;
            adds.reverse();
            reversed = true;
        }
        else if(mode === 'lastchild'  || mode === '-2'){
            mode = this.$manipulate_lastchild;
        }
        else if(mode === 'replace'){
            mode = this.$manipulate_replace;
        }
        else{
            throw `illegale position (move/moved/paste/pasted)`;
        }

        const selection = [];
        for(const ref of refs){
            const length = selection.length;
            for(const add of adds){
                const result = mode(ref, copy ? this.$cloneElement(add) : add);
                reversed ? selection.splice(length, 0, result) : selection.push(result);
            }
            copy = true;
        }

        return this.$chain(selection);
    }


    $manipulate_prev(ref, add){
        return ref.parentElement.insertBefore(add, ref);
    }


    $manipulate_next(ref, add){
        return ref.parentElement.insertBefore(add, ref.nextElementSibling);
    }


    $manipulate_firstchild(ref, add){
        return ref.insertBefore(add, ref.firstElementChild);
    }


    $manipulate_lastchild(ref, add){
        return ref.appendChild(add);
    }


    $manipulate_replace(ref, add){
        return ref.parentElement.replaceChild(add, ref);
    }


    copy(){
        const selection = this.map(v => this.$cloneElement(v));
        return this.$chain(selection);
    }


    remove(){
        const selection = this.map(v => v.parentElement.removeChild(v));
        return this.$chain(selection);
    }


    $cloneElement(element){
        if(element.nodeType !== 1){
            return element.cloneNode(true);
        }
        const clone      = element.cloneNode(true);
        const cloneAll   = Array.from(clone.querySelectorAll("*")).concat(clone);
        const elementAll = Array.from(element.querySelectorAll("*")).concat(element);
        for(let i = 0; i < elementAll.length; i++){
            if(!('mquery' in elementAll[i])){
                continue;
            }
            cloneAll[i].mquery = elementAll[i].mquery;
            if(!('event' in elementAll[i].mquery)){
                continue;
            }
            for(let name in elementAll[i].mquery.event){
                elementAll[i].mquery.event[name].forEach(args => cloneAll[i].addEventListener(...args));
            }
        }
        return clone;
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


    toggleAttr(name, value = ''){
        return this.$each(v => v.hasAttribute(name) ? v.removeAttribute(name) : v.setAttribute(name, value));
    }


    hasAttr(name){
        return this.some(v => ('hasAttribute' in v) && v.hasAttribute(name));
    }


    css(name, value, priority = ''){
        if(value !== undefined){ // CSSを1つ設定
            if(value.match(/\!important/i)){
                priority = 'important';
                value    = value.replace(/\!important/i, '').trim();
            }
            return this.$each(v => v.style.setProperty(name, value, priority));
        }
        else if($.type(name) === 'string'){
            if(name.includes(':')){ // CSSを複数設定
                return this.$each(v => v.style.cssText += name);
            }
            else{ // CSSを1つ取得
                return this.$exists('style') ? window.getComputedStyle(this[0])[name] : undefined;
            }
        }
        else if($.type(name) === 'object'){ //CSSを複数設定(再帰)
            Object.keys(name).forEach(k => this.css(k, name[k]));
        }

        return this;
    }


    removeCSS(name){
        return (name !== undefined)  ?  this.$each(v => v.style.removeProperty(name))  :  this.$each(v => v.style.cssText = '');
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


    geo(more){
        if(!this.$exists('getBoundingClientRect')){
            return {};
        }
        const rect = this[0].getBoundingClientRect();
        rect.x += rect.scrollX = window.scrollX; //html.clientLeft を引く https://jsperf.com/offset-vs-getboundingclientrect/20
        rect.y += rect.scrollY = window.scrollY; //html.clientTop  を引く http://n-yagi.0r2.net/script/2009/06/post_14.html

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
        return this.$setCollection(v => this.$traverseFamily(v, 'parentElement', selector, true));
    }


    parents(selector){
        return this.$setCollection(v => this.$traverseFamily(v, 'parentElement', selector));
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


    next(selector){
        return this.$setCollection(v => this.$traverseFamily(v, 'nextElementSibling', selector, true));
    }


    nextAll(selector){
        return this.$setCollection(v => this.$traverseFamily(v, 'nextElementSibling', selector));
    }


    siblings(selector){
        return this.$setCollection(v => [...this.$traverseChild(v.parentElement, 'children', selector)].filter(v2 => v2 !== v));
    }


    * $traverseFamily(el, prop, selector, alone){
        while(el = el[prop]){
            if(!selector || el.matches(selector)){
                yield el;
            }
            if(alone){
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

        const args = [];
        args[0] = name.replace(/\-.*/, '').toLowerCase();
        args[1] = function(e){
            if(option.find && !e.target.matches(option.find)){ // デリゲート
                return;
            }
            if('name' in e && e.name.includes('-') && e.name !== name){ // trigger()から呼ばれて名前付きの場合
                return;
            }
            e.memo = ('memo' in e) ? e.memo : option.memo;
            e.name = ('name' in e) ? e.name : name;
            e.this = e.currentTarget;
            e.self = e.target;
            if(handler.call(e.this, e) === false){
                e.stopPropagation();
                e.preventDefault();
            }
            if(option.once){ // 消す
                const eventdata = $.data(e.this, `mquery.event.${name}`).filter(v => v !== args);
                eventdata.length ? $.data(e.this, `mquery.event.${name}`, eventdata) : $.removeData(e.this, `mquery.event.${name}`);
            }
        };
        args[2] = {once: Boolean(option.once), passive: Boolean(option.passive), capture: Boolean(option.capture)};


        for(const v of this){
            if(!('addEventListener' in v)){
                continue;
            }

            if($.hasData(v, `mquery.event.${name}`)){
                if(name.includes('-')){ //名前付きは重複登録禁止
                    throw `mQuery.on('${name}') event is already registered.`;
                }
                v.mquery.event[name].push(args);
            }
            else{
                $.data(v, `mquery.event.${name}`, [args]);
            }
            v.addEventListener(args[0], args[1], args[2]);
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
        if(name !== undefined){ // イベントを1つ解除
            for(const v of this){
                if(!$.hasData(v, `mquery.event.${name}`)){
                    continue;
                }
                $.data(v, `mquery.event.${name}`).forEach(args => v.removeEventListener(...args));
                $.removeData(v, `mquery.event.${name}`);
            }
        }
        else{ // イベントをすべて解除
            for(const v of this){
                if(!$.hasData(v, `mquery.event`)){
                    continue;
                }
                for(const name in v.mquery.event){
                    $.data(v, `mquery.event.${name}`).forEach(args => v.removeEventListener(...args));
                    $.removeData(v, `mquery.event.${name}`);
                }
            }
        }
        return this;
    }


    trigger(name, option = {}){
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
            const event = new Event(name.replace(/\-.*/, '').toLowerCase(), {bubbles, cancelable, composed});
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


    deserialize(str){
        if(this.name() !== 'form'){
            return;
        }
        this[0].querySelectorAll("input[type='checkbox']").forEach(v => v.checked = false);
        const select = [];

        for(const [k, v] of new URLSearchParams(str)){
            const element = this[0].querySelector(`[name='${k}']`);
            if(!element){
                continue;
            }

            if(element.type === 'radio' || element.type === 'checkbox'){
                const ckecked = this[0].querySelector(`[name='${k}'][value='${v}']`);
                if(chekced){
                    ckecked.ckecked = true;
                }
            }
            else if(element.tagName === 'SELECT'){
                if(!select.includes(element)){ //初期化(multiple対策)
                    element.querySelectorAll('option').forEach(v => v.selected = false);
                    select.push(element);
                }
                const selected = element.querySelector(`[value='${v}']`);
                if(selected){
                    selected.selrcted = true;
                }
            }
            else{
                element.value = v;
            }
        }

        return this;
    }


    async send(addParam = {}){
        if(this.name() !== 'form'){
            return;
        }
        const method  = this[0].method  || 'GET';
        const action  = this[0].action  || '';
        let   url     = action.replace(/#.*/, '') || location.href;
        let   body;

        const param = new FormData(this[0]);
        Object.keys(addParam).forEach(k => param.append(k, addParam[k]));

        if(method.match(/(GET|HEAD)/i)){
            url = url.replace(/\?.*/, '') + '?' + new URLSearchParams(param);
        }
        else{
            body = param;
        }

        const response = await window.fetch(url, {method, body, credentials:'include'});
        
        if(response.ok){
            return (response.headers.get('Content-Type') === 'application/json') ? response.json() : response.text();
        }
        else{
            return response;
        }
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
            v.style.display = $.data(v, 'mquery.display') || '';
        }
    }


    $hide(v){
        if(v.style.display !== 'none'){
            $.data(v, 'mquery.display', window.getComputedStyle(v)['display']);
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


$.script = async function(...queue){
    for(const v of queue){
        await $.script.load(v);
    }
};


$.module = async function(...queue){
    for(const v of queue){
        await $.script.load(v, true);
    }
};


$.script.load = function(url, module = false){
    function async(success, fail){
        const script = document.createElement('script');
        if(module){
            script.type = 'module';
        }
        script.onload  = success;
        script.onerror = fail;
        script.src     = url;
        document.body.appendChild(script);
    }
    return new Promise(async);
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


$.data = function(object, prop, value){
    const props = prop.split('.');
    prop = props.pop();

    if(value === undefined){ // 取得動作
        for(const v of props){
            if(!(v in object)){
                return;
            }
            object = object[v];
        }
        return object[prop];
    }
    else{ // 設定動作
        for(const v of props){
            if(!(v in object)){
                object[v] = {};
            }
            object = object[v];
        }
        object[prop] = value;
    }
};


$.removeData = function(object, prop){
    const props = prop.split('.');
    prop = props.pop();

    for(const v of props){
        if(!(v in object)){
            return;
        }
        object = object[v];
    }
    delete object[prop];
};


$.hasData = function(object, prop){
    for(const v of prop.split('.')){
        if(!(v in object)){
            return false;
        }
        object = object[v];
    }
    return true;
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
