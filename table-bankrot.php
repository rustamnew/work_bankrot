<?
require_once __DIR__ . '/simplexlsx.class.php';

function request($id) {
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://bankrot.fedresurs.ru/backend/cmpbankrupts?searchString='.$id.'&isActiveLegalCase=null&limit=15&offset=0',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
      'Request-Line:GET /backend/cmpbankrupts?searchString=2330086193&isActiveLegalCase=null&limit=15&offset=0 HTTP/1.1',
      'Referer:https://bankrot.fedresurs.ru',
      'User-Agent:PostmanRuntime/7.29.0',
      'Accept:*/*',
      'Postman-Token:c2778514-c315-4e29-be64-623753848b17',
      'Host:bankrot.fedresurs.ru',
      'Connection:keep-alive',
      'Accept-Encoding:gzip, deflate',
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  $obj = json_decode($response, true);


  if($obj['pageData'][0]) {

  

  echo '<tr>';
    echo '<td>';
      print_r($obj['pageData'][0]['name']);
    echo '</td>';

    echo '<td>';
      print_r($obj['pageData'][0]['inn']);
    echo '</td>';

    echo '<td>';
      print_r($obj['pageData'][0]['ogrn']);
    echo '</td>';

    echo '<td>';
      print_r($obj['pageData'][0]['category']);
    echo '</td>';

    echo '<td>';
      print_r($obj['pageData'][0]['region']);
    echo '</td>';

    echo '<td>';
      print_r($obj['pageData'][0]['arbitrManagerFio']);
    echo '</td>';

    echo '<td>';
      print_r($obj['pageData'][0]['address']);
    echo '</td>';

    echo '<td>';
      print_r($obj['pageData'][0]['lastLegalCase']['number']);
    echo '</td>';
  echo '</tr>';

  } else {
    echo '<tr>';
      echo '<td>';
        echo 'Не найдено: ' . $id;
      echo '</td>';
    echo '</tr>';
  }
}?>

<?if ( $xlsx = SimpleXLSX::parse('test.xlsx')):?>
  <div class="wrap">
    <table class="table-bankrot">
        <thead>
            <tr>
                <th>Имя</th>
                <th>ИНН</th>
                <th>ОГРН</th>
                <th>Категория</th>
                <th>Регион</th>
                <th>Арбитражный управляющий</th>
                <th>Адрес</th>
                <th>Номер судебного дела</th>
            </tr>
        </thead>
      <tbody>

      <?
      $array = $xlsx->rows();
      $n = 1;
      ?>

      <?foreach($array as $arItem):?>
        <?$id = $arItem[1];?>

        <?if(gettype($id) === 'integer' and $id !== 0):?>

          <?
          request($id);
          $n = $n + 1;
          ?>
        <?endif;?>
      <?endforeach;?>
      </tbody>
    </table>
  </div>

<?else:?>
  <?echo SimpleXLSX::parse_error();?>
<?endif;?>


<style>
.wrap {
  width: 92%;
  height: 100%;
  margin: 0 auto;
  overflow: auto;
}
table.table-bankrot{
	width: 100%;
	border-collapse:collapse;
	border-spacing:0;
	height: auto;
}

table.table-bankrot td,
table.table-bankrot th {
	padding: 3px;
	width: 30px;
	height: 35px;
  text-align: center;

  border: 1px solid #dee2e6;
}
table.table-bankrot td:first-child {
  white-space: nowrap;
}
table.table-bankrot th {
	background: #dee2e6;
  border: 1px solid black; 
	/*color: #fff; */
	color: black; 
	font-weight: bold;
}
</style>
