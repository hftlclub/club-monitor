var jsKeyboard = {
    settings: {
        buttonClass: "button", // default button class
        onclick: "jsKeyboard.write();", // default onclick event for button
        keyClass: "key", // default key class used to define style of text of the button
        text: {
            close: "close"
        }
    },
    "keyboard": [], // different keyboards can be set to this variable in order to switch between keyboards easily.
    init: function(elem, keyboard) {
        jsKeyboard.keyboard["default"] = jsKeyboard.defaultKeyboard;
        jsKeyboard.keyboardLayout = elem;

        if (keyboard != null && keyboard != undefined)
            jsKeyboard.generateKeyboard(keyboard);
        else
            jsKeyboard.generateKeyboard("default");

        jsKeyboard.addKeyDownEvent();

        $(".button").click(function () {
            if( ($(this).parent("keyboardCapitalLetter")) && (!($(this).hasClass("button_capitalletterleft"))) && (!($(this).hasClass("button_symbolsright")))  && (!($(this).hasClass("button_numberleft")))){
                console.log("Test");
                jsKeyboard.changeToSmallLetter();
            }
        });
    },
    focus: function(t) {
        jsKeyboard.currentElement = $(t);
        jsKeyboard.show();
    },
    keyboardLayout: "", // it shows the html element where keyboard is generated
    currentKeyboard: "default", // it shows the which keyboard is used. If it's not set default keyboard is used.
    currentElement: null,
    generateKeyboard: function(keyboard) {
        var bClass = "";
        var kClass = "";
        var onclick = "";
        var text = "";

        var s = "";
        s += "<div id=\"keyboard\">";
        s += "<div id=\"keyboardHeader\">";
        s += "<div onclick=\"jsKeyboard.hide();\"><span>" + jsKeyboard.settings.text.close + "</span><span class=\"closex\"> X</span></div>"
        s += "</div>";

        /*capital letter*/
        s += "<div id=\"keyboardCapitalLetter\">";
        $.each(jsKeyboard.keyboard[keyboard].capitalLetter, function(i, key) {
            generate(key);
        });
        s += "</div>";

        /*small letter */
        s += "<div id=\"keyboardSmallLetter\">";
        $.each(jsKeyboard.keyboard[keyboard].smallLetter, function(i, key) {
            generate(key);
        });
        s += "</div>";

        /*number*/
        s += "<div id=\"keyboardNumber\">";
        $.each(jsKeyboard.keyboard[keyboard].number, function(i, key) {
            generate(key);
        });
        s += "</div>";

        /*symbols*/
        s += "<div id=\"keyboardSymbols\">";
        $.each(jsKeyboard.keyboard[keyboard].symbols, function(i, key) {
            generate(key);
        });
        s += "</div>";

        function generate(key) {
            bClass = key.buttonClass == undefined ? jsKeyboard.settings.buttonClass : key.buttonClass;
            kClass = key.keyClass == undefined ? jsKeyboard.settings.keyClass : key.keyClass;
            onclick = key.onclick == undefined ? jsKeyboard.settings.onclick.replace("()", "(" + key.value + ")") : key.onclick;

            text = (key.isChar != undefined || key.isChar == false) ? key.value : String.fromCharCode(key.value);

            s += "<div class=\"" + bClass + "\" onclick=\"" + onclick + "\"><div class=\"" + kClass + "\">" + text + "</div></div>";

            bClass = ""; kClass = ""; onclick = ""; text = "";
        }

        $("#" + jsKeyboard.keyboardLayout).html(s);
    },
    addKeyDownEvent: function() {
        $("#keyboardCapitalLetter > div.button, #keyboardSmallLetter > div.button, #keyboardNumber > div.button, #keyboardSymbols > div.button").
            bind('mousedown', (function() { $(this).addClass("buttonDown"); })).
            bind('mouseup', (function() { $(this).removeClass("buttonDown"); })).
            bind('mouseout', (function() { $(this).removeClass("buttonDown"); }));
    },
    changeToSmallLetter: function() {
        $("#keyboardCapitalLetter,#keyboardNumber,#keyboardSymbols").css("display", "none");
        $("#keyboardSmallLetter").css("display", "block");
    },
    changeToCapitalLetter: function() {
        $("#keyboardCapitalLetter").css("display", "block");
        $("#keyboardSmallLetter,#keyboardNumber,#keyboardSymbols").css("display", "none");
    },
    changeToNumber: function() {
        $("#keyboardNumber").css("display", "block");
        $("#keyboardSymbols,#keyboardCapitalLetter,#keyboardSmallLetter").css("display", "none");
    },
    changeToSymbols: function() {
        $("#keyboardCapitalLetter,#keyboardNumber,#keyboardSmallLetter").css("display", "none");
        $("#keyboardSymbols").css("display", "block");
    },
    write: function(m) {
        var t = jsKeyboard.currentElement.val();
        t += String.fromCharCode(m);
        jsKeyboard.currentElement.val(t);
    },
    del: function() {
        var t = jsKeyboard.currentElement.val();
        jsKeyboard.currentElement.val(t.substring(0, t.length - 1));
    },
    enter: function() {
        var t = jsKeyboard.currentElement.val();
        jsKeyboard.currentElement.val(t + "\n");
    },
    writeSpecial: function(m) {
        var t = jsKeyboard.currentElement.val();
        t += m;
        jsKeyboard.currentElement.val(t);
    },
    show: function() {
        $("#keyboard").animate({ "bottom": "0" }, "slow", function() { });
        $("#keyboard").removeClass("hidden");
    },
    hide: function() {
        //$("#keyboard").animate({ "bottom": "-380px" }, "slow", function() { });
        $("#keyboard").addClass("hidden");
    },
    defaultKeyboard: {
        capitalLetter:
            [
        // 1st row
               { value: 81 },{ value: 87 },{ value: 69 },{ value: 82 },{ value: 84 },{ value: 90 },
               { value: 85 },{ value: 73 },{ value: 79 },{ value: 80 },{ value: 220 },
               { value: "del", isChar: "false", onclick: "jsKeyboard.del()", buttonClass: "button button_del", keyClass: "key key_del" },
        // 2nd row
               { value: 65, buttonClass: "button button_a" },{ value: 83 },{ value: 68 },{ value: 70 },
               { value: 71 },{ value: 72 },{ value: 74 },{ value: 75 },{ value: 76 },{ value: 214 },{ value: 196 },
               { value: "Enter", isChar: "false", buttonClass: "button button_enter", onclick: "jsKeyboard.enter();", keyClass: "key key_enter" },
        // 3rd row
               { value: "abc", isChar: "false", buttonClass: "button button_smallletter", onclick: "jsKeyboard.changeToSmallLetter();", keyClass: "key key_smallletter" },
               { value: 89 },{ value: 88 },{ value: 67 },{ value: 86 },{ value: 66 },{ value: 78 },
               { value: 77 },
               // for , . ' { value: 44 },{ value: 46 },{ value: 39 },
        // 4th row
               { value: 32, buttonClass: "button button_space" },
               { value: "#$@", isChar: "false", buttonClass: "button button_symbolsright", onclick: "jsKeyboard.changeToSymbols();", keyClass: "key key_symbols" },
               { value: "123", isChar: "false", buttonClass: "button button_numberleft", onclick: "jsKeyboard.changeToNumber();", keyClass: "key key_number" },

            ],
        smallLetter: [
        // 1st row
                { value: 113 },{ value: 119 },{ value: 101 },{ value: 114 },{ value: 116 },
                { value: 122 },{ value: 117 },{ value: 105 },{ value: 111 },{ value: 112 },{ value: 252 },
                { value: "del", isChar: "false", onclick: "jsKeyboard.del()", buttonClass: "button button_del", keyClass: "key key_del" },
        // 2nd row
                { value: 97, buttonClass: "button button_a" },{ value: 115 },{ value: 100 },{ value: 102 },
                { value: 103 },{ value: 104 },{ value: 106 },{ value: 107 },{ value: 108 }, { value: 246 }, { value: 228 },
                { value: "Enter", isChar: "false", buttonClass: "button button_enter", onclick: "jsKeyboard.enter();", keyClass: "key key_enter" },
        // 3rd row
                { value: "ABC", isChar: "false", buttonClass: "button button_capitalletterleft", onclick: "jsKeyboard.changeToCapitalLetter();", keyClass: "key key_capitalletterleft" },
                { value: 121 },{ value: 120 },{ value: 99 },{ value: 118 },{ value: 98 },
                { value: 110 },{ value: 109 },
                // for , . ' { value: 44 },{ value: 46 },{ value: 39 },
        // 4th row
                { value: 32, buttonClass: "button button_space" },
                { value: "#$@", isChar: "false", buttonClass: "button button_symbolsright", onclick: "jsKeyboard.changeToSymbols();", keyClass: "key key_symbols" },
                { value: "123", isChar: "false", buttonClass: "button button_numberleft", onclick: "jsKeyboard.changeToNumber();", keyClass: "key key_number" },
            ],
        number: [
        // 1st row
                { value: 49 },{ value: 50 },{ value: 51 },{ value: 52 },{ value: 53 },{ value: 54 },
                { value: 55 },{ value: 56 },{ value: 57 },{ value: 48 },{ value: 124 },
                { value: "del", isChar: "false", onclick: "jsKeyboard.del()", buttonClass: "button button_del", keyClass: "key key_del" },
        // 2nd row
                { value: 45, buttonClass: "button button_dash" },{ value: 47 },{ value: 58 },{ value: 59 },
                { value: 40 },{ value: 41 },{ value: 36 },{ value: 38 },{ value: 64 },

                { value: "ABC", isChar: "false", buttonClass: "button button_capitalletterleft", onclick: "jsKeyboard.changeToCapitalLetter()", keyClass: "key key_capitalletterleft" },
                { value: 63 },
                { value: "Enter", isChar: "false", buttonClass: "button button_enter", onclick: "jsKeyboard.enter();", keyClass: "key key_enter" },
		//3rd row
				{ value: 33 },{ value: 34 },{ value: 92 },{ value: 42 },{ value: 61 },{ value: 43 },
                { value: "ABC", isChar: "false", buttonClass: "button button_capitalletterright", onclick: "jsKeyboard.changeToCapitalLetter();", keyClass: "key key_capitalletterright" },
        // 4th row
                //{ value: "#$@", isChar: "false", buttonClass: "button button_symbolsleft", onclick: "jsKeyboard.changeToSymbols();", keyClass: "key key_symbols" },
                { value: 32, buttonClass: "button button_space" },
                { value: "ABC", isChar: "false", buttonClass: "button button_capitalletterleft", onclick: "jsKeyboard.changeToCapitalLetter();", keyClass: "key key_capitalletterleft" },
                { value: "#$@", isChar: "false", buttonClass: "button button_symbolsright", onclick: "jsKeyboard.changeToSymbols();", keyClass: "key key_symbols" }
            ],
        symbols: [
        // 1st row
            { value: 91 },{ value: 93 },{ value: 123 },{ value: 125 },{ value: 35 },{ value: 37 },
            { value: 94 },{ value: 42 },{ value: 43 },{ value: 61 },{ value: 92 },
            { value: "del", isChar: "false", onclick: "jsKeyboard.del()", buttonClass: "button button_del", keyClass: "key key_del" },
        // 2nd row
            { value: 95, buttonClass: "button button_underscore" },{ value: 92 },{ value: 124 },{ value: 126 },
            { value: 60 },{ value: 62 },
            { value: "&euro;", isChar: "false", onclick: "jsKeyboard.writeSpecial('&euro;');" },
            { value: 163 },{ value: 165 },{ value: 44 },{ value: 46 },
            { value: "Enter", isChar: "false", buttonClass: "button button_enter", onclick: "jsKeyboard.enter();", keyClass: "key key_enter" },
        // 3rd row
            { value: "ABC", isChar: "false", buttonClass: "button button_capitalletterright", onclick: "jsKeyboard.changeToCapitalLetter();", keyClass: "key key_capitalletterright" },
            { value: "123", isChar: "false", buttonClass: "button button_numberleft", onclick: "jsKeyboard.changeToNumber();", keyClass: "key key_number" },
            { value: 63 },{ value: 33 },{ value: 39 },{ value: 34 },{ value: 59 },
            { value: 32, buttonClass: "button button_space" },
            { value: "ABC", isChar: "false", buttonClass: "button button_capitalletterleft", onclick: "jsKeyboard.changeToCapitalLetter();", keyClass: "key key_capitalletterleft" },
            { value: "123", isChar: "false", buttonClass: "button button_numberright", onclick: "jsKeyboard.changeToNumber();", keyClass: "key key_number" }
         ]
    }
};



