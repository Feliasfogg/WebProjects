/**
 * Created by pavel on 19-Mar-16.
 */
window.onload = function () {
    function colorMy() {
        // this.style.color="red";
        this.style.cssText = "color:red; font-size:18px;";
        this.setAttribute("data", "test");//установка атрибута на узел
        if (this.innerHTML == "Пункт 1") {
            this.innerHTML = "Хуй";
        }
    }

    function removeMy() {
        this.removeAttribute("style");
    }

    run.onclick = function () {
        var html = document.documentElement;
        var childs = html.childNodes;//получение всех узлов
        //var childs = html.children;//собирает только тэги, без text и комментариев
        var body = document.body.children;
        var AllLi = body[1].getElementsByTagName("li");
        for (var i = 0; i < AllLi.length; ++i) {
            AllLi[i].addEventListener("click", colorMy);
        }

        console.log(html);
        console.log(childs);
    }

    test.onclick = function () {
        var AllLi = document.body.children[1].getElementsByTagName("li");
        for (var i = 0; i < AllLi.length; ++i) {
            AllLi[i].addEventListener("click", colorMy);
            AllLi[i].addEventListener("contextmenu", removeMy);
        }
    }
    add.onclick = function () {
        var div = document.body.children[0];
        div.innerHTML = "Переименован";
        var ul = document.createElement("ul");
        for (var i = 0; i < 4; ++i) {
            var li = document.createElement("li");
            li.innerHTML = "Подпункт " + i;
            ul.appendChild(li);//добавление элемента в конец набора
        }
        div.appendChild(ul);
    }

    var contentShow = true;
    show.onclick = function () {
        var content = document.getElementById("content");
        if (contentShow) {
            content.style.cssText = "display:none";
            contentShow = false;
        } else {
            content.style.cssText = "display:block";
            contentShow = true;
        }
    }
}