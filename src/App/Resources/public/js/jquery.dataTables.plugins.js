jQuery.extend( jQuery.fn.dataTableExt.oSort, {
   "datetime-us-pre": function ( a ) {
       //var b = a.match(/(\d{1,2})\/(\d{1,2})\/(\d{2,4}) (\d{1,2}):(\d{1,2}) (am|pm|AM|PM|Am|Pm)/);
       var b = a.match(/(\d{1,2})\/(\d{1,2})\/(\d{2,4})/);
       month = b[1],
       day = b[2],
       year = b[3],
       hour = b[4],
       min = b[5],
       ap = b[6];
 
       //if(hour == '12') hour = '0';
       //if(ap == 'pm') hour = parseInt(hour, 10)+12;
 
       if(year.length == 2){
           if(parseInt(year, 10)<70) year = '20'+year;
           else year = '19'+year;
       }
       if(month.length == 1) month = '0'+month;
       if(day.length == 1) day = '0'+day;
       //if(hour.length == 1) hour = '0'+hour;
       //if(min.length == 1) min = '0'+min;
 
       //var tt = year+month+day+hour+min;
       var tt = year+month+day;
       return  tt;
   },
   "datetime-us-asc": function ( a, b ) {
       return a - b;
   },
 
   "datetime-us-desc": function ( a, b ) {
       return b - a;
   }
});
 
jQuery.fn.dataTableExt.aTypes.unshift(
   function ( sData )
   {
       //if (sData !== null && sData.match(/\d{1,2}\/\d{1,2}\/\d{2,4} \d{1,2}:\d{1,2} (am|pm|AM|PM|Am|Pm)/))
       if (sData !== null && sData.match(/\d{1,2}\/\d{1,2}\/\d{2,4}/))
       {
 
           return 'datetime-us';
       }
       return null;
   }
);

/* //////////////////
CURRENCY
////////////////// */

$.fn.dataTableExt.oSort['currency-asc'] = function (a, b) {
'use strict';

var x, y;

/* Remove any commas (assumes that if present all strings will have a fixed number of d.p) */
x = (a === "-" || a === "--" || a === '' || a.toLowerCase().replace('/', '') === 'na') ? -1 : a.replace(/,/g, "");
y = (b === "-" || b === "--" || b === '' || b.toLowerCase().replace('/', '') === 'na') ? -1 : b.replace(/,/g, "");

/* Remove the currency sign */
if (typeof x === "string" && isNaN(x.substr(0, 1), 10)) {
x = x.substring(1);
}
if (typeof y === "string" && isNaN(y.substr(0, 1), 10)) {
y = y.substring(1);
}

/* Parse and return */
x = parseFloat(x, 10);
y = parseFloat(y, 10);

return x - y;
};
$.fn.dataTableExt.oSort['currency-desc'] = function (a, b) {
'use strict';

var x, y;

/* Remove any commas (assumes that if present all strings will have a fixed number of d.p) */
x = (a === "-" || a === "--" || a === '' || a.toLowerCase().replace('/', '') === 'na') ? -1 : a.replace(/,/g, "");
y = (b === "-" || b === "--" || b === '' || b.toLowerCase().replace('/', '') === 'na') ? -1 : b.replace(/,/g, "");

/* Remove the currency sign */
if (typeof x === "string" && isNaN(x.substr(0, 1), 10)) {
x = x.substring(1);
}
if (typeof y === "string" && isNaN(y.substr(0, 1), 10)) {
y = y.substring(1);
}

/* Parse and return */
x = parseFloat(x, 10);
y = parseFloat(y, 10);

return y - x;
};