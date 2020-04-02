<?php
class Zend_View_Helper_GetEdad extends Zend_View_Helper_Abstract
{

    public function getEdad()
    {
        $Listaarea = '<div  class="form-group">';
        $Listaarea .= '<label for="comboEdad">Edad:</label>';

        $Listaarea .= '<select class="form-control form-control-sm js-example-basic-single" name="comboEdad" id="comboEdad" required autocomplete="off">
                     <option value="" ></option>';
        for ($i = 0; $i <= 100; $i++):
            $Listaarea .= "<option value='" . $i . "' >" . $i . "</option>";
        endfor;
        $Listaarea .= "</select>
                        <div class='valid-feedback'></div>
                        <div class='invalid-feedback'></div>
                        </div>";

        return $Listaarea;

    }
}
