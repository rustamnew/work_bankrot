

<?require_once __DIR__ . '/simplexlsx.class.php';?>




<script type="text/javascript" src="jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="script.js"></script>

<?
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
    $link = 'https://fedresurs.ru/company/'.$obj['pageData'][0]['guid'];

    echo '<tr>';
      echo '<td>';?>
        <a  href="<?=$link;?>" target="_blank">
          <?print_r($obj['pageData'][0]['name']);?>
        </a>
      <?echo '</td>';

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
<?//echo '<pre>';print_r($_FILES['excel-file']);echo '</pre>';?>

<div class="main">
	<h1>Загрузка данных о банкротстве</h1>

  <?
  $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  ?>

	<form class="file-form" action="<?=$actual_link;?>" method="POST" enctype="multipart/form-data" id="excel-file-form">
		<input type="file" name="excel-file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
    <input type="submit" value="Загрузить">
	</form>

	<div class="result">

  </div>
</div>

<?if($_FILES['excel-file']['tmp_name']):?>
  <?if ($xlsx = SimpleXLSX::parse($_FILES['excel-file']['tmp_name'])):?>
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
<?endif;?>

<!-- :: Loading -->
<div class="loading">
  <div class="loading-box">
    <div class="lds-ring">
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>
  </div>
</div>


<style>
.main {
  width: 92%;
  margin: 0 auto;
  margin-bottom: 50px;
}
.wrap {
  width: 92%;
  height: 100%;
  margin: 0 auto;
  overflow: auto;
  margin-bottom: 50px;
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


/* :: Loading */
.loading {
    position: fixed;
    background-color: #FFF;
    left: 0;
    top: 0;
    z-index: 99999;
    width: 100%;
    height: 100%;
    -webkit-transition: unset;
    -o-transition: unset;
    transition: unset;
}
.loading .loading-box {
    position: absolute;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
}
.lds-ring {
    display: inline-block;
    position: relative;
    width: 80px;
    height: 80px;
}
.lds-ring div {
    -webkit-box-sizing: border-box;
            box-sizing: border-box;
    display: block;
    position: absolute;
    width: 64px;
    height: 64px;
    margin: 8px;
    border: 8px solid $theme-color1;
    border-radius: 50%;
    -webkit-animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
            animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    border-color: $theme-color1 transparent transparent transparent;
}
.lds-ring div:nth-child(1) {
    -webkit-animation-delay: -0.45s;
            animation-delay: -0.45s;
}
.lds-ring div:nth-child(2) {
    -webkit-animation-delay: -0.3s;
            animation-delay: -0.3s;
}
.lds-ring div:nth-child(3) {
    -webkit-animation-delay: -0.15s;
            animation-delay: -0.15s;
}
.lds-ring div {
    border: 8px solid #2652DC;
    border-color: #2652DC transparent transparent transparent;
}
@-webkit-keyframes lds-ring {
    0% {
        -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
    }

    100% {
        -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
    }
}
@keyframes lds-ring {
    0% {
        -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
    }

    100% {
        -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
    }
}
</style>
