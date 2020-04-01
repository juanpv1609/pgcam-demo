<?php
class Zend_View_Helper_GetDiagnosticos extends Zend_View_Helper_Abstract
{
    public function getDiagnosticos()
    {
        $cadena = '<table class="table table-sm table-bordered ">
        <thead>
           <tr>
              <th></th>
              <th>DIAGNOSTICO</th>
              <th>CIE</th>
              <th>PRE</th>
              <th>DEF</th>
           </tr>
        </thead>
        <tbody>';
        for ($i=1; $i <=3 ; $i++) { //forma las 3 filas para los 3 diagnosticos
            $cadena .= '<tr><th>'.$i.'</th>
               <td>
                  <input type="text" id="diagnostico'.$i.'" name="diagnostico'.$i.'"
                     class="form-control form-control-sm border-0 text-xxs "
                     placeholder="Buscar por codigo o descripcion..." autocomplete="off"
                     onkeyup="if (event.keyCode == 13) getDiagnostico(this);">
                  <div id="lista'.$i.'"></div>
               </td>
               <td class="py-2">
                  <strong id="cod'.$i.'"></strong>
               </td>
               <td>
                  <div class="custom-control custom-checkbox small text-center">
                     <input type="radio" class="custom-control-input" id="pre'.$i.'"
                        name="tipo_diagnostico'.$i.'" value="1" >
                     <label class="custom-control-label" for="pre'.$i.'"></label>
                  </div>
               </td>
               <td>
                  <div class="custom-control custom-checkbox small text-center">
                     <input type="radio" class="custom-control-input" id="def'.$i.'"
                        name="tipo_diagnostico'.$i.'" value="2">
                     <label class="custom-control-label" for="def'.$i.'"></label>
                  </div>
               </td>
            </tr>';
        }
        $cadena .= '  </tbody>
     </table>';
        return $cadena;
    }
}
