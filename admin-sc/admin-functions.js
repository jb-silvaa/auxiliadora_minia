function epoch_to_hh_mm_ss(epoch){
    return new Date(epoch*1000).toISOString().substr(12,7)
}

function tiemposEspera(tipo) {
    var datos = {
        "tipo_dato":tipo,
        "tipo":"obtenerResumen"
    };
    var jsonData = $.ajax({
        url: '/gestion/admin/controller/controller.php',
        type:"POST",
        data:datos
    }).done(function (results) {
    var labels = []; var data = [];
    var i = 0;
    for (i = 0; i < Object.keys(results).length; i++){
        line = results[i];
        labels.push(line.id);
        data.push(line.tiempo);
    }
    var ctx = canvas_espera.getContext('2d');
    var config = {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Tiempos de Espera',
                data: data,
                backgroundColor: 'rgba(0, 119, 204, 0.3)',
                borderColor: "rgba(142, 231, 33, 1)",
                lineTension: 0,
                fill: false
            }]
        },
        options:{
            responsive: true,
            maintainAspectRatio: false,
            scales:{
                yAxes:[{
                    ticks: {
                        userCallback: function(v) {return epoch_to_hh_mm_ss(v)}
                    }
                }]
            },
            tooltips:{
                callbacks: {
                    label: function(tooltipItem, data) {
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + epoch_to_hh_mm_ss(tooltipItem.yLabel)
                    }
                }
            }
        }
    };

    var chart = new Chart(ctx, config);
    });
};
function tiemposAtencion(tipo) {
    var datos = {
        "tipo_dato":tipo,
        "tipo":"obtenerResumen"
    };
    var jsonData = $.ajax({
        url: '/gestion/admin/controller/controller.php',
        type:"POST",
        data:datos
    }).done(function (results) {
      // Split timestamp and data into separate arrays
    var labels = []; var data = [];
    var i = 0;
    for (i = 0; i < Object.keys(results).length; i++){
        line = results[i];
        labels.push(line.id);
        data.push(line.tiempo);
    }

    var ctx = canvas_atencion.getContext('2d');
    var config = {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Tiempos de Atencion',
                data: data,
                backgroundColor: 'rgba(0, 119, 204, 0.3)',
                borderColor: "rgba(142, 231, 33, 1)",
                lineTension: 0,
                fill: false
            }]
        },
        options:{
            responsive: true,
            maintainAspectRatio: false,
            scales:{
                yAxes:[{
                    ticks: {
                        userCallback: function(v) {return epoch_to_hh_mm_ss(v)}
                    }
                }]
            },
            tooltips:{
                callbacks: {
                    label: function(tooltipItem, data) {
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + epoch_to_hh_mm_ss(tooltipItem.yLabel)
                    }
                }
            }
        }
    };

    var chart = new Chart(ctx, config);
    });
};

function pacientesDia(){
    var datos = {
        "tipo_dato":tipo,
        "tipo":"pacientesDia"
    };
    var jsonData = $.ajax({
        url: '/gestion/admin/controller/controller.php',
        type:"POST",
        data:datos
    }).done(function (results) {
      // Split timestamp and data into separate arrays
    var labels = []; var data = [];
    var i = 0;
    for (i = 0; i < Object.keys(results).length; i++){
        line = results[i];
        labels.push(line.id);
        data.push(line.tiempo);
    }

    var ctx = canvas_atencion.getContext('2d');
    var config = {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Tiempos de Atencion',
                data: data,
                backgroundColor: 'rgba(0, 119, 204, 0.3)',
                borderColor: "rgba(23, 159, 163, 1)",
                lineTension: 0,
                fill: false
            }]
        },
        options:{
            responsive: true,
            maintainAspectRatio: false,
            scales:{
                yAxes:[{
                    ticks: {
                        userCallback: function(v) {return epoch_to_hh_mm_ss(v)}
                    }
                }]
            },
            tooltips:{
                callbacks: {
                    label: function(tooltipItem, data) {
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + epoch_to_hh_mm_ss(tooltipItem.yLabel)
                    }
                }
            }
        }
    };

    var chart = new Chart(ctx, config);
    });
};