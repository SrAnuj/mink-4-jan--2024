<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Manage order Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1>Manage Notifications</h1>
            <small><?php echo display('Store Target Report') ?></small>
            <ol class="breadcrumb">
                <li>
                    <a href="#">
                        <i class="pe-7s-home"></i>
                        <?php echo display('home') ?>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <?php echo display('Store Target Report') ?>
                    </a>
                </li>
                <li class="active">
                    <?php echo display('Manage Store Target Report') ?>
                </li>
            </ol>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card-body">
                    <div class="table-responsive">
                     <table id="data_table" class="table">
                <thead>
                    <tr>
                        <th>Store</th>
                        <th>Completed Targets</th>
                        <th>Pending Targets</th>
                        <th>Total Targets</th>
                        <th>Target Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
                </table>
                 </div>

                </div>
                 
            </div>
        
        </div>
        <div id="chart_div" style="width: 100%; height: 500px;"></div>
        
    </section>
</div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Fetch data from your PHP function
            var jsonData = <?php echo $json_data; ?>;

            var tableBody = document.getElementById('data_table').getElementsByTagName('tbody')[0];

                jsonData.forEach(function (store) {
                    var row = tableBody.insertRow(tableBody.rows.length);
                    var cell1 = row.insertCell(0);
                    var cell2 = row.insertCell(1);
                    var cell3 = row.insertCell(2);
                    var cell4 = row.insertCell(3);
                    var cell5 = row.insertCell(4);

                    cell1.innerHTML = store.store_name;
                    cell2.innerHTML = '<span class="badge badge-warning">'+store.total_completed_targets+'</span>';
                    cell3.innerHTML = store.total_pending_targets;
                    cell4.innerHTML = store.total_targets;
                    cell5.innerHTML = '<span class="badge badge-primary">'+store.target_percentage+'</span>';
                });

            // Create a data table
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Store');
            data.addColumn('number', 'Completed Targets');
            data.addColumn('number', 'Pending Targets');
            data.addColumn('number', 'Total Targets');
            data.addColumn('number', 'Target Percentage');

            // Add data to the data table
            jsonData.forEach(function(store) {
                data.addRow([
                    store.store_name,
                    parseFloat(store.total_completed_targets),
                    parseFloat(store.total_pending_targets),
                    parseFloat(store.total_targets),
                    parseFloat(store.target_percentage)
                ]);
            });

            // Create a chart
            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

            // Define chart options (customize as needed)
            var options = {
                title: 'Store Targets',
                hAxis: { title: 'Store' },
                vAxis: { title: 'Targets' },
                seriesType: 'bars',
                series: { 4: { type: 'line' } }
            };

            // Draw the chart
            chart.draw(data, options);
        }
    </script>
<!-- Manage order End -->