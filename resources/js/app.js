import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import $ from 'jquery';
import jszip from 'jszip';
import pdfMake from 'pdfmake/build/pdfmake';
import vfsFonts from 'pdfmake/build/vfs_fonts';

// **Importaciones de DataTables y Plugins**
// Asegúrate de que las dependencias de DataTables se inyectan correctamente
import 'datatables.net'; 
import 'datatables.net-buttons'; 
import 'datatables.net-buttons/js/buttons.html5.js';
import 'datatables.net-buttons/js/buttons.print.js';

window.Alpine = Alpine;
Alpine.start();

// Configuración PDFMake (necesario para la exportación a PDF de DataTables)
// CRÍTICO: Asignación de fuentes para que pdfMake funcione correctamente
pdfMake.vfs = vfsFonts;
window.JSZip = jszip; // DataTables necesita JSZip globalmente para exportar Excel

document.addEventListener("DOMContentLoaded", () => {

    const animationConfig = {
        duration: 1800,
        easing: 'easeOutBounce',
        delay: (context) => context.dataIndex * 150
    };

    // Paleta de colores para gráficos de barra, pie y doughnut
    const colorPalette = ["#4f46e5", "#111827", "#6B7280", "#9CA3AF", "#D1D5DB", "#FBBF24", "#DC2626"];


    // ------------------------------------
    // INICIALIZACIÓN DE GRÁFICOS DINÁMICOS
    // ------------------------------------
    
    // Leemos la configuración de gráficos inyectada por Laravel Blade (si existe)
    const dynamicConfig = window.chartsConfig || {}; 

    // Iteramos sobre las claves (IDs de los canvas) del objeto dinámico
    Object.keys(dynamicConfig).forEach(id => {
        const cfg = dynamicConfig[id];
        const ctx = document.getElementById(id);
        
        if (ctx) {
            let chartOptions = { responsive: true, animation: animationConfig };
            let chartData;
            
            // Genera una etiqueta legible
            const dataLabel = id.replace('Chart', '').replace(/([A-Z])/g, ' $1').trim();

            if (cfg.type === 'line') {
                // Configuración para gráfico de línea
                chartData = {
                    labels: cfg.labels,
                    datasets: [{
                        label: dataLabel,
                        data: cfg.data,
                        borderColor: "#111827",
                        backgroundColor: "rgba(17,24,39,0.2)",
                        tension: 0.3,
                        fill: true
                    }]
                };
            } else {
                // Configuración para bar, pie, doughnut
                chartData = {
                    labels: cfg.labels,
                    datasets: [{
                        label: dataLabel,
                        data: cfg.data,
                        // Usamos la paleta de colores para los segmentos
                        backgroundColor: colorPalette.slice(0, cfg.labels.length) 
                    }]
                };
            }

            new Chart(ctx, { type: cfg.type, data: chartData, options: chartOptions });
        }
    });

    // ------------------------------------
    // INICIALIZACIÓN DE DATATABLES
    // ------------------------------------

    const reservasTable = document.getElementById('reservasTable');
    
    // Solución al problema de botones duplicados: 
    // CRÍTICO: Comprobamos si la tabla ya ha sido inicializada como DataTables antes de intentar hacerlo de nuevo.
    if (reservasTable && !$.fn.dataTable.isDataTable(reservasTable)) {
        $(reservasTable).DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            pageLength: 10,
            order: [[0,'desc']],
            // CRÍTICO: Se reemplaza la URL de carga externa por la configuración JSON en línea 
            // para evitar errores de CSP o de carga de AJAX.
            language: {
                "processing": "Procesando...",
                "lengthMenu": "Mostrar _MENU_ registros",
                "zeroRecords": "No se encontraron resultados",
                "emptyTable": "Ningún dato disponible en esta tabla",
                "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "infoFiltered": "(filtrado de un total de _MAX_ registros)",
                "infoPostFix": "",
                "search": "Buscar:",
                "url": "",
                "thousands": ",",
                "loadingRecords": "Cargando...",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "aria": {
                    "sortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                "buttons": {
                    "copy": "Copiar",
                    "csv": "CSV",
                    "excel": "Excel",
                    "pdf": "PDF",
                    "print": "Imprimir",
                    "colvis": "Columnas"
                }
            }
        });
    }
});
