'use strict';

(function ($) {
    var methods = {
        init: function (event, vars) {
            let errors = [];
            if (methods.validateLogin(vars.login) !== true) errors.push(methods.validateLogin(vars.login));
            if (methods.validateEmail(vars.email) !== true) errors.push(methods.validateEmail(vars.email));
            if (methods.validatePassword(vars.passwords) !== true) errors.push(methods.validatePassword(vars.passwords));
            methods.checkErrors(errors, event, $(this));
            methods.show_nonifications(errors);
            errors = null;
        },
        validateLogin: function (input) {
            let value = input.val();
            let regular = /^[a-zA-Z][a-zA-Z0-9-_\.]{1,20}$/;
            if (value == '') {
                methods.show_error(input);
                return 'Login is empty';
            } else if (regular.test(value)) {
                return true;
            } else {
                methods.show_error(input);
                return 'Login is not valid';
            }
        },
        validateEmail: function (input) {
            let value = input.val();
            let regular = /^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/;
            if (value == '') {
                methods.show_error(input);
                return 'Email is empty';
            } else if (regular.test(value)) {
                return true;
            } else {
                methods.show_error(input);
                return 'Type valid email';
            }
        },
        validatePassword: function (inputs) {
            let password = inputs[0];
            let re_password = inputs[1];
            if (password.val() == '' || re_password.val() == '') {
                methods.show_error(inputs);
                return 'Some of password fields is empty!';
            } else if (password.val() !== re_password.val()) {
                methods.show_error(inputs);
                return 'Passwords is not match';
            } else {
                return true;
            }
        },
        checkErrors: function (errors, event, element) {
            if (errors.length !== 0) {
                event.preventDefault();
                methods.animation(element, 'shake');
            } else {
                this.submit();
            }
        },
        animation: function (elemID, animationName) {
            $(elemID).addClass('animated ' + animationName);
            $(elemID).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $(this).removeClass('animated ' + animationName);
            });
        },
        show_error: function (element) {
            if (Array.isArray(element) == true) {
                element.forEach(function (val, k) {
                    val.addClass('fail');
                });
            } else {
                element.addClass('fail');
            }
        },
        show_nonifications: function (errors) {
            let margin = 10;
            let timeout = 0;
            let timeout2 = 1000;
            let msgElement = $('.error__msg');
            if (msgElement.length > 0) {
                msgElement.each(function () {
                    $(this).remove();
                });
            }
            errors.forEach(function (val, k) {
                let tpl = '<div class="error__msg" style="top:' + margin + 'px" id="nonification_' + k + '"><h2>ERROR!</h2><p>' + val.trim() + '</p></div>';
                $('body').append(tpl);
                let item = $('#nonification_' + k);
                setTimeout(function () {
                    item.fadeIn().addClass('animated bounceInDown').delay(timeout2).fadeOut();
                }, timeout);
                timeout += 300;
                timeout2 += 300;
                margin = margin + item.height() + 20;
            });
        }
    };
    $.fn.validate = function () {
        return methods.init.apply(this, arguments);
    }
})(jQuery);

$('input').on('input', function () {
    $(this).removeClass('fail');
});