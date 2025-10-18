import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import $ from 'jquery';
import jszip from 'jszip';
import pdfMake from 'pdfmake/build/pdfmake';
import vfsFonts from 'pdfmake/build/vfs_fonts';
import 'datatables.net'; 
import 'datatables.net-buttons'; 
import 'datatables.net-buttons/js/buttons.html5.js';
import 'datatables.net-buttons/js/buttons.print.js';

// --- Alpine ---
window.Alpine = Alpine;
Alpine.start();

// --- PDF & JSZip para DataTables ---
pdfMake.vfs = vfsFonts;
window.JSZip = jszip;

// --- Charts ---
const initCharts = () => {
    const animationConfig = {
        duration: 1800,
        easing: 'easeOutBounce',
        delay: (context) => context.dataIndex * 150
    };
    const colorPalette = ["#4f46e5", "#111827", "#6B7280", "#9CA3AF", "#D1D5DB", "#FBBF24", "#DC2626"];
    const dynamicConfig = window.chartsConfig || {};

    Object.keys(dynamicConfig).forEach(id => {
        const cfg = dynamicConfig[id];
        const ctx = document.getElementById(id);
        if (!ctx) return;

        let chartOptions = { responsive: true, animation: animationConfig };
        let chartData;

        const dataLabel = id.replace('Chart', '').replace(/([A-Z])/g, ' $1').trim();

        if (cfg.type === 'line') {
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
            chartData = {
                labels: cfg.labels,
                datasets: [{
                    label: dataLabel,
                    data: cfg.data,
                    backgroundColor: colorPalette.slice(0, cfg.labels.length)
                }]
            };
        }

        new Chart(ctx, { type: cfg.type, data: chartData, options: chartOptions });
    });
};

// --- DataTables ---
const initDataTables = () => {
    const reservasTable = document.getElementById('reservasTable');
    if (reservasTable && !$.fn.dataTable.isDataTable(reservasTable)) {
        $(reservasTable).DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            pageLength: 10,
            order: [[0,'desc']],
            language: {
                processing: "Procesando...",
                lengthMenu: "Mostrar _MENU_ registros",
                zeroRecords: "No se encontraron resultados",
                emptyTable: "Ningún dato disponible en esta tabla",
                info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                infoFiltered: "(filtrado de un total de _MAX_ registros)",
                search: "Buscar:",
                paginate: { first: "Primero", last: "Último", next: "Siguiente", previous: "Anterior" },
                buttons: { copy: "Copiar", csv: "CSV", excel: "Excel", pdf: "PDF", print: "Imprimir" }
            }
        });
    }
};

// --- DOMContentLoaded ---
document.addEventListener("DOMContentLoaded", () => {
    initCharts();
    initDataTables();
});
