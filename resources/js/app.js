import './bootstrap';
import $ from 'jquery';
import 'datatables.net-dt/css/jquery.dataTables.css';
import dt from 'datatables.net-dt';

window.$ = window.jQuery = $;

$(document).ready(function () {
    $('#myTable').DataTable();
});
