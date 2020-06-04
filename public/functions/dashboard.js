//--------- CONFIGURACIONES GLOBALES ----------------------
Chart.defaults.global.defaultFontFamily = 'Segoe UI', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

let PRIMARY = 'rgba(78, 115, 223, 0.5)' ;
let SECONDARY = 'rgba(133, 135, 150, 0.5)';
let SUCCESS = 'rgba(28, 200, 138, 0.5)'; 
let WARNING = 'rgba(246, 194, 62, 0.5)';
let DANGER = 'rgba(231, 74, 59, 0.5)';
let PURPLE = 'rgba(102, 16, 242, 0.5)';
let INFO = 'rgba(54, 185, 204, 0.5)';
let ORANGE = 'rgba(253, 126, 20, 0.5)';
function ultima_conexion(){
   var fecha = $('#ultima_conexion_dias').val();
   //console.log(moment(fecha, "YYYY-MM-DD HH:mm:ss").fromNow())
   $('#ultima_conexion').text(moment(fecha, "YYYY-MM-DD h:m:s").fromNow());
}


// mapa de camas
function mapaDeCamas(especialidad_id) {
   var dir = $('#dir').val();
   $.ajax(
      {
         dataType: "html",
         type: "POST",
         url: dir + "/index/mapacamas", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
         data: "especialidad_id=" + especialidad_id, //Se añade el parametro de busqueda del medico
         beforeSend: function (data) {
            $("#data_MapaCamas").html('<div class="d-flex justify-content-center  text-primary"><i class="fas fa-spinner fa-pulse fa-5x "></i></div>');
         },
         success: function (requestData) {//armar la tabla
            $("#data_MapaCamas").html(requestData);
         },
         error: function (requestData, strError, strTipoError) {
            //alert(requestData, strError, strTipoError)
         },
         complete: function (requestData, exito) { //fin de la llamada ajax.

         }
      });

}
//------- GRAFICAS ---------
function camasPorServicio() {
   opcion = typeof opcion !== 'undefined' ?  opcion : 3; // 3 es un valor por defecto e incluye todos los datos
   var dir = $('#dir').val();
   var especialidad = [];
   var disponibles = [];
   var ocupadas = [];
   var desinfeccion = [];
   $.ajax(
      {
         dataType: "json",
         type: "POST",
         url: dir + "/index/camasporservicio", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
         data: "opcion="+opcion,     //Se añade el parametro de busqueda del medico
         beforeSend: function (data) {
         },
         success: function (requestData) {//armar la tabla
            var datos = requestData.data;
            for (let i in datos) {
               especialidad[i] = datos[i].especialidad_nombre;
               disponibles[i] = datos[i].disponibles;
               ocupadas[i] = datos[i].ocupadas;
               desinfeccion[i] = datos[i].desinfeccion;
            }
            chartBar(especialidad, disponibles,ocupadas,desinfeccion);

         },
         error: function (requestData, strError, strTipoError) {
            //alert(strError + " " + strTipoError);
         },
         complete: function (requestData, exito) { //fin de la llamada ajax.

         }
      });
}
function camasEstado() {
   var dir = $('#dir').val();
   var estado = [];
   var cantidad_camas = [];
   $.ajax(
      {
         dataType: "json",
         type: "POST",
         url: dir + "/index/camasestado", // ruta donde se encuentra nuestro action que procesa la peticion XmlHttpRequest
         //Se añade el parametro de busqueda del medico
         beforeSend: function (data) {
         },
         success: function (requestData) {//armar la tabla
            var datos = requestData.data;
            //console.log(datos);
            for (let i in datos) {
               estado[i] = datos[i].cama_estado_descripcion;
               cantidad_camas[i] = datos[i].cuenta_camas;
            }
            chartPie(estado,cantidad_camas);

         },
         error: function (requestData, strError, strTipoError) {
            //alert(strError + " " + strTipoError);
         },
         complete: function (requestData, exito) { //fin de la llamada ajax.

         }
      });
}
function chartBar(labels, disponibles, ocupadas, desinfeccion) {
   let bg = [SECONDARY, DANGER, WARNING, PRIMARY];

   var disponibles = {
      label: 'Disponibles',
      data: disponibles,
      backgroundColor: bg[0],
      borderColor: bg[0],
      borderWidth: 1
   }
   var ocupadas = {
      label: 'Ocupadas',
      data: ocupadas,
      backgroundColor: bg[1],
      borderColor: bg[1],
      borderWidth: 1
   }
   var desinfeccion = {
      label: 'Desinfeccion',
      data: desinfeccion,
      backgroundColor: bg[2],
      borderColor: bg[2],
      borderWidth: 1
   }
   let opciones = {
      responsive: true,
      scales: {
         xAxes: [{
            scaleLabel: {
               display: false,
               labelString: 'Numero de Camas'
            },
            ticks: {
               beginAtZero: true
            }
         }]
      },
      legend: {
         display: true
      },
      tooltips: {
         titleMarginBottom: 10,
         titleFontColor: '#6e707e',
         titleFontSize: 14,
         backgroundColor: "rgb(255,255,255)",
         bodyFontColor: "#858796",
         borderColor: '#dddfeb',
         borderWidth: 1,
         xPadding: 15,
         yPadding: 15,
         displayColors: false,
         caretPadding: 10,
         callbacks: {
            label: function (tooltipItem, chart) {
               var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
               return datasetLabel + ': ' + tooltipItem.xLabel;
            }
         }
      }
   }


   var ctx = document.getElementById('myChart').getContext('2d');

   var myChart = new Chart(ctx, {
      type: 'horizontalBar',
      data: {
         labels: labels,
         datasets: [disponibles, ocupadas, desinfeccion]
      },
      options: opciones
   });
   // myChart.update();
}

function chartPie(labels, data) {
   // Pie Chart Example

   var ctx = document.getElementById("myPieChart");
   var myPieChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
         labels: labels,
         datasets: [{
            label: 'Numero de camas',
            data: data,
            backgroundColor: [SECONDARY, DANGER, WARNING],
            borderColor: [SECONDARY, DANGER, WARNING]
         }],
      },
      options: {
         responsive: true,
         maintainAspectRatio: false,
         tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: true,
            caretPadding: 10,
         },
         legend: {
            display: true,
            position: 'bottom'
         },
         cutoutPercentage: 50
      },
   });
   //myChart.render();
}

//------------

