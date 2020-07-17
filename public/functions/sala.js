function mueveReloj() {
   momentoActual = new Date()
   hora = momentoActual.getHours()
   minuto = (momentoActual.getMinutes()<10?'0':'')+momentoActual.getMinutes()
   //segundo = momentoActual.getSeconds()

   horaImprimible = hora + " : " + minuto //+ " : " + segundo 
   $('#reloj').text(horaImprimible);

   setTimeout("mueveReloj()", 1000)
} 
function dt(){
   // sala de espera
   var table = $('#TablePacienteCamaSala').DataTable({
      searching: false,
      info: false,
      //ordering: true,
      lengthChange: false,
      paging: true,
      stateSave: true,
      "pagingType": "numbers"
   });
   var tiempo=5000;
   // Get the page info, so we know what the last is
   var pageInfo = table.page.info(),
      // Set the ending interval to the last page
      endInt = pageInfo.pages,
      // Current page
      currentInt = 0,
      // Start an interval to go to the "next" page every 3 seconds
      interval = setInterval(function () {
         $('#dataBody').removeClass('animated slideInRight');

         console.log('inicio: ' + currentInt + ' fin: ' + endInt)
         // Increment the current page int
         currentInt++; 
         // "Next" ...
         table.page(currentInt).draw('page');
         $('#dataBody').addClass('animated slideInRight');
         // If were on the last page, reset the currentInt to the first page #
         if (currentInt === endInt) {
            currentInt = 0;
         } 
          
      }, tiempo); // 3 seconds   
      
   console.log('total pacientes: ' + pageInfo.recordsTotal)
   $('#idCaption').text('Pacientes: ' + pageInfo.recordsTotal);
   //setTimeout(window.location.reload(), 100000)
}
