/**
 * 打印js对象
 * @param obj
 */
function alertObj(obj) {
    var output = "";
    for (var i in obj) {
        var property = obj[i];
        output += i + " = " + property + "\n";
    }
    alert(output);
}

/**
 * js跳转
 * @param url
 * @returns {boolean}
 */
function redirection(url) {
    location.href = url;
    return false;
}
function refresh() {
    var url = location.href;

    var timestamp = (new Date()).valueOf();
    if (url.indexOf('?') >= 0) {
        url += ("&random=" + timestamp);
    } else {
        url += ("?random=" + timestamp);
    }
    location.href = url;
}


/**
 * 仅能输入数字
 * @param keyCode
 * @returns {boolean}
 */
function isNumber(keyCode) {
    // 数字
    if (keyCode >= 48 && keyCode <= 57) {
        return true;
    }
    // 小数字键盘
    if (keyCode >= 96 && keyCode <= 105) {
        return true;
    }
    // Backspace键
    if (keyCode == 8) {
        return true;
    }
    return false;
}


$.fn.numeral = function () {
    $(this).css("ime-mode", "disabled");
    this.bind("keypress", function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);  //兼容火狐 IE
        if (!$.browser.msie && (e.keyCode == 0x8))  //火狐下不能使用退格键
        {
            return;
        }
        return code >= 48 && code <= 57;
    });
    this.bind("blur", function () {
        if (this.value.lastIndexOf(".") == (this.value.length - 1)) {
            this.value = this.value.substr(0, this.value.length - 1);
        } else if (isNaN(this.value)) {
            this.value = "";
        }
    });
    this.bind("paste", function () {
        var s = clipboardData.getData('text');
        if (!/\D/.test(s));
        value = s.replace(/^0*/, '');
        return false;
    });
    this.bind("dragenter", function () {
        return false;
    });
    this.bind("keyup", function () {
        if (/(^0+)/.test(this.value)) {
            this.value = this.value.replace(/^0*/, '');
        }
    });
};


/**
 * 验证邮箱
 */
function isEMail(email) {
    return (/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(email));
}

//判断是否为数字
function isNum(s) {
    if (s != null && s != "") {
        return !isNaN(s);
    }
    return false;
}

/*判断输入是否为合法的手机号码*/
function isPhoneNum(inputString) {
    var partten = /^1[1,2,3,4,5,6,7,8,9,0]\d{9}$/;
    if (partten.test(inputString)) {
        return true;
    }
    else {
        return false;
        //alert('不是手机号码');
    }
}
function isQQ(qq) {
    var partten = /^\s*[.0-9]{5,15}\s*$/;
    if (!partten.test(qq)) {
        return false;
    } else {
        return true;
    }
}

/**
 * 判断是否为中文，汉字
 * @param inutString
 * @param mode:1 只要有汉字就返回true   2; 全部为汉字才返回true
 * @returns {*|boolean}
 */
function isChina(inutString, mode) {
    if (mode == 2) {
        return /^[\u4e00-\u9fa5]+$/.test(inutString);
    } else {
        return inutString.match(/[\u4e00-\u9fa5]/g) && !(/^[a-zA-Z0-9]/.test(inutString));//
    }
}

/**
 *
 * @param obj
 * @param k
 * @returns {boolean}
 */
function isset(obj, k) {
    if ((typeof(obj) == "array" || typeof(obj) == "object") && typeof(k) != 'undefined') {
        return !(typeof(obj[k]) == "undefined");
    } else {
        return !(typeof(obj) == 'undefined');
    }
}

/**
 * 格式化秒数
 * @param format
 * @param timestamp
 * //Y-m-d H:i:s
 */
function format_time(format, timestamp) {
    var day = parseInt(timestamp / 86400);
    timestamp -= day * 86400;
    var hour = parseInt(timestamp / 3600);
    timestamp -= hour * 3600;
    var minute = parseInt(timestamp / 60);
    var second = timestamp % 60;

    hour = ('00' + hour).substr(-2);
    minute = ('00' + minute).substr(-2);
    second = ('00' + second).substr(-2);
    var ret_str = format.replace('d', day);
    ret_str = ret_str.replace('H', hour);
    ret_str = ret_str.replace('i', minute);
    ret_str = ret_str.replace('s', second);
    return ret_str;
}


/**
 * //Y-m-d H:i:s
 * 格式化时间
 * @param format
 * @param time
 * @returns {string|XML|*|void}
 */
function format_date(format, time) {
    if (typeof(time) == "object") {
        date = time;
    } else if (typeof(time) == 'undefined') {
        date = new Date();
    } else {
        date = new Date(time);
    }
    var Y = date.getUTCFullYear();
    var m = ('00' + (date.getUTCMonth() + 1)).substr(-2);
    var d = ('00' + date.getUTCDate()).substr(-2);
    var H = ('00' + date.getUTCHours()).substr(-2);
    var i = ('00' + date.getUTCMinutes()).substr(-2);
    var s = ('00' + date.getUTCSeconds()).substr(-2);
    ret_str = format.replace('Y', Y);
    ret_str = ret_str.replace('m', m);
    ret_str = ret_str.replace('d', d);
    ret_str = ret_str.replace('H', H);
    ret_str = ret_str.replace('i', i);
    ret_str = ret_str.replace('s', s);
    return ret_str;
    //Y-m-d H:i:s
}

/**
 * 字符串去头尾空格
 * @param str
 * @returns {XML|*|string|void}
 */
function trim(str) { //删除左右两端的空格
    return str.replace(/(^\s*)|(\s*$)/g, "");
}
function ltrim(str) { //删除左边的空格
    return str.replace(/(^\s*)/g, "");
}
function rtrim(str) { //删除右边的空格
    return str.replace(/(\s*$)/g, "");
}

function checkIdcard(idcard) {
    var Errors = new Array(
        "验证通过!",
        "身份证号码位数不对!",
        "身份证号码出生日期超出范围或含有非法字符!",
        "身份证号码校验错误!",
        "身份证地区非法!"
    );
    var area = {
        11: "北京",
        12: "天津",
        13: "河北",
        14: "山西",
        15: "内蒙古",
        21: "辽宁",
        22: "吉林",
        23: "黑龙江",
        31: "上海",
        32: "江苏",
        33: "浙江",
        34: "安徽",
        35: "福建",
        36: "江西",
        37: "山东",
        41: "河南",
        42: "湖北",
        43: "湖南",
        44: "广东",
        45: "广西",
        46: "海南",
        50: "重庆",
        51: "四川",
        52: "贵州",
        53: "云南",
        54: "西藏",
        61: "陕西",
        62: "甘肃",
        63: "青海",
        64: "宁夏",
        65: "新疆",
        71: "台湾",
        81: "香港",
        82: "澳门",
        91: "国外"
    };

    var idcard, Y, JYM;
    var S, M;
    var idcard_array = new Array();
    idcard_array = idcard.split("");

    //地区检验
    if (area[parseInt(idcard.substr(0, 2))] == null) {
        return Errors[4];
    }


    //身份号码位数及格式检验
    switch (idcard.length) {
        case 15:
            if ((parseInt(idcard.substr(6, 2)) + 1900) % 4 == 0 || ((parseInt(idcard.substr(6, 2)) + 1900) % 100 == 0 && (parseInt(idcard.substr(6, 2)) + 1900) % 4 == 0 )) {
                ereg = /^[1-9][0-9]{5}[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}$/;//测试出生日期的合法性
            } else {
                ereg = /^[1-9][0-9]{5}[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}$/;//测试出生日期的合法性
            }

            if (ereg.test(idcard)) {
                return 0;
            }
            else {
                return Errors[2];
            }
            break;
        case 18:
            //18位身份号码检测
            //出生日期的合法性检查
            //闰年月日:((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))
            //平年月日:((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))
            if (parseInt(idcard.substr(6, 4)) % 4 == 0 || (parseInt(idcard.substr(6, 4)) % 100 == 0 && parseInt(idcard.substr(6, 4)) % 4 == 0 )) {
                ereg = /^[1-9][0-9]{5}19[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}[0-9Xx]$/;//闰年出生日期的合法性正则表达式
            } else {
                ereg = /^[1-9][0-9]{5}19[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}[0-9Xx]$/;//平年出生日期的合法性正则表达式
            }
            if (ereg.test(idcard)) {//测试出生日期的合法性
                //计算校验位
                S = (parseInt(idcard_array[0]) + parseInt(idcard_array[10])) * 7
                    + (parseInt(idcard_array[1]) + parseInt(idcard_array[11])) * 9
                    + (parseInt(idcard_array[2]) + parseInt(idcard_array[12])) * 10
                    + (parseInt(idcard_array[3]) + parseInt(idcard_array[13])) * 5
                    + (parseInt(idcard_array[4]) + parseInt(idcard_array[14])) * 8
                    + (parseInt(idcard_array[5]) + parseInt(idcard_array[15])) * 4
                    + (parseInt(idcard_array[6]) + parseInt(idcard_array[16])) * 2
                    + parseInt(idcard_array[7]) * 1
                    + parseInt(idcard_array[8]) * 6
                    + parseInt(idcard_array[9]) * 3;
                Y = S % 11;
                M = "F";
                JYM = "10X98765432";
                M = JYM.substr(Y, 1);//判断校验位
                if (M == idcard_array[17]) {
                    return 0;
                } //检测ID的校验位
                else {
                    return Errors[3];
                }
            }
            else {
                return Errors[2];
            }

            break;
        default:
            return Errors[1];
            break;
    }
}