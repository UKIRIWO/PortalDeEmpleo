<?php $this->layout('layouts/Layout', ['titulo' => $titulo]); ?>

<?php $this->start('contenido') ?>

    <h1>Política de Privacidad de WorkSphere</h1>
    
    <p>La presente Política de Privacidad describe cómo WorkSphere recopila, utiliza y protege la información personal de sus usuarios (Empresas y Alumnos) de acuerdo con la legislación vigente. Al utilizar nuestro Portal, usted acepta las condiciones descritas a continuación.</p>
    
    <hr>

    <section id="responsable">
        <h2>1. Identidad y Contacto del Responsable</h2>
        <ul>
            <li><b>Responsable del Tratamiento:</b> [Tu Nombre o Nombre de la Entidad/Empresa]</li>
            <li><b>Contacto de Privacidad:</b> [Tu Correo Electrónico de Contacto para Privacidad]</li>
        </ul>
    </section>

    <section id="informacion-recopilada">
        <h2>2. Información que Recopilamos</h2>
        <p>Recopilamos la siguiente información personal, vital para el funcionamiento del portal:</p>
        
        <h3>De Empresas:</h3>
        <ul>
            <li>Nombre, dirección, persona y datos de contacto (teléfono y email), y el logotipo.</li>
        </ul>
        
        <h3>De Alumnos:</h3>
        <ul>
            <li>Datos de identificación (nombre, apellidos, DNI, fecha de nacimiento).</li>
            <li>Datos de contacto (email, dirección, foto).</li>
            <li>Datos curriculares o académicos (estudios realizados y currículum vitae).</li>
        </ul>
    </section>

    <section id="finalidad-base-legal">
        <h2>3. Finalidad y Base Legal del Tratamiento</h2>
        <p>El tratamiento de sus datos se basa en los siguientes fundamentos legales:</p>
        
        <ul>
            <li><b>Ejecución del Servicio Contratado:</b> Para gestionar el perfil, permitir la publicación de ofertas (Empresas) y la búsqueda y solicitud de las mismas (Alumnos).</li>
            <li><b>Consentimiento Explícito:</b> Para el tratamiento de la información sensible del Alumno (como el DNI y el currículum) y, crucialmente, para la <b>cesión de datos a la Empresa</b> cuando el Alumno solicita una oferta.</li>
        </ul>
    </section>

    <section id="cesion-datos">
        <h2>4. Cesión y Comunicación de Datos a Terceros</h2>
        <p>La única cesión de datos que realiza WorkSphere es la siguiente:</p>
        
        <ul>
            <li><b>A quién:</b> A la <b>Empresa</b> que ha publicado una oferta.</li>
            <li><b>Qué datos:</b> El perfil completo del Alumno (datos básicos, de contacto y el currículum vitae).</li>
            <li><b>Cuándo:</b> Únicamente cuando el Alumno solicita <b>voluntariamente</b> una oferta de dicha Empresa.</li>
        </ul>

        <p>Al solicitar una oferta, el Alumno otorga su <b>consentimiento explícito</b> para que la Empresa acceda a su información con el fin de evaluar su candidatura. La Empresa receptora se convierte, a partir de ese momento, en la <b>Responsable del Tratamiento</b> de esa información para su proceso de selección.</p>
    </section>

    <section id="seguridad-conservacion">
        <h2>5. Medidas de Seguridad y Conservación</h2>
        <p>WorkSphere se compromete a proteger sus datos con medidas de seguridad técnicas y organizativas adecuadas para evitar su pérdida, alteración o acceso no autorizado.</p>
        <p>Sus datos se conservarán mientras mantenga su perfil activo. Si cancela su cuenta o permanece inactivo, sus datos se conservarán durante un plazo máximo de <b>[Indicar un plazo, ej: 2 años]</b> para atender posibles responsabilidades legales, tras lo cual serán eliminados de forma segura.</p>
    </section>
    
    <section id="derechos-interesado">
        <h2>6. Derechos del Interesado</h2>
        <p>Usted tiene derecho a ejercer sus derechos de <b>Acceso, Rectificación, Supresión</b> (Derecho al Olvido), <b>Oposición, Limitación del Tratamiento y Portabilidad</b> de sus datos.</p>
        <p>Puede ejercer estos derechos, así como revocar cualquier consentimiento, enviando una comunicación al <b>Contacto de Privacidad</b> indicado en el punto 1.</p>
    </section>

<?php $this->stop() ?>