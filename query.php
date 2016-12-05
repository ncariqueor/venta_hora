<?php
$query = "A continuación se presenta la query utilizada para el Informe de Performance Paris Internet por hora:<br><br>

<b>SELECT SVVIF03.NUMORDEN, SVVIF03.FECHANT, SVVIF03.HORANT, SVVIF03.TIPVTA, SVVIF03.MONTOVTA, SVVIF03.NROCUENTA,<br>
       SVVIF03.TIPOPAG, SVVIF04.CODDESP, SVVIF03.ESTORDEN, SVVIF03.SUBESTOC, Svvif04.Canvend*Svvif04.Precuni AS PXQ,<br>
       svvif04.codsku, Svvif04.Canvend, Svvif04.Precuni <br><br>

FROM RDBPARIS2.CECEBUGD.SVVIF03 SVVIF03, RDBPARIS2.CECEBUGD.SVVIF04 SVVIF04 <br><br>

WHERE (SVVIF03.NUMORDEN = SVVIF04.NUMORDEN) AND (SVVIF03.TIPVTA = SVVIF04.TIPVTA) <br>
                AND (SVVIF03.TIPVTA IN (1, 2, 15)) AND (SVVIF03.fechant = dia actual en formato AAAAmmdd)</b>";

echo "<p style='font-family: Calibri;'>" . $query . "</p>";