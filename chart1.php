<html>
  <head>
    <script type='text/javascript' src='https://www.google.com/jsapi'></script>
    <script type='text/javascript'>
      google.load('visualization', '1', {packages:['table']});
      google.setOnLoadCallback(drawTable);
      function drawTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Song Name');
        data.addColumn('string', 'Artist');
        data.addRows([
          ['Jim',   'Bow'],
          ['Alice', 'AAAAAA'],
          ['Bob',  'aassadadasdasdas']
        ]);

        var table = new google.visualization.Table(document.getElementById('table_div'));
        table.draw(data, {showRowNumber: true});
      }
    </script>
  </head>

  <body>
    <div id='table_div'></div>
  </body>
</html>
