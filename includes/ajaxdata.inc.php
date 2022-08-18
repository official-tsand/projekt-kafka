<?php
  function sendAjaxData()
    {
      $arrayArguments = func_get_args();
      $data = array();

      for ($i = 0; $i < count($arrayArguments); $i++)
        {
          $data += [$arrayArguments[$i][0] => $arrayArguments[$i][1]];
        }

      echo json_encode($data);
    }
