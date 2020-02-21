/**
 * The difference between now and a prior or an
 * upcoming event, returned as readable text for humans.
 * 
 * 
 * @param {Number} unixTime milliseconds that have elapsed since 
 *                          00:00:00 Thursday, 1 January 1970
 * @param {boolean} ms set true if unixTime is not in milliseconds
 * @return {String}
 */
function diffForHumans(unixTime, ms) {
  
   // Adjust for milliseconds
   ms = ms || false;
   unixTime = (ms) ? unixTime * 1000 : unixTime;
 
   var d = new Date();
   var diff = Math.abs(d.getTime() - unixTime);
   var intervals = {
     y: diff / (365 * 24 * 60 * 60 * 1 * 1000),
     m: diff / (30.5 * 24 * 60 * 60 * 1 * 1000),
     d: diff / (24 * 60 * 60 * 1 * 1000),
     h: diff / (60 * 60 * 1 * 1000),
     i: diff / (60 * 1 * 1000),
     s: diff / (1 * 1000),
   }
 
   Object.keys(intervals).map(function(value, index) {
     return intervals[value] = Math.floor(intervals[value]);
   })
 
   var unit;
   var count;
 
   switch (true) {
     case intervals.y > 0:
       count = intervals.y;
       unit = 'año';
       break;
     case intervals.m > 0:
       count = intervals.m;
       unit = 'mese';
       break;
     case intervals.d > 0:
       count = intervals.d;
       unit = 'dia';
       break;
     case intervals.h > 0:
       count = intervals.h;
       unit = 'hora';
       break;
     case intervals.i > 0:
       count = intervals.i;
       unit = 'minuto';
       break;
     default:
       count = intervals.s;
       unit = 'segundo';
       break;
 
   }
 
   if(count > 1) {
     unit = unit + 's';
   }
 
   if(count === 0) {
     return 'ahora';
   }
 
   return count + ' ' + unit + ((unixTime > d.getTime()) ? ' from now' : ' atras');
 }
 var now = new Date().getTime();
var eighteen_days_ago = now - (18 * 24 * 60 * 60 * 1 * 1000);
var four_hours_ago= now - (4 * 60 * 60 * 1 * 1000);
console.log(diffForHumans(now));
console.log(diffForHumans(eighteen_days_ago));
console.log(diffForHumans(four_hours_ago));
console.log(diffForHumans(1455574551, true));
