<?php
$uploadDir = 'uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = uniqid('img_', true) . '.' . $fileExtension;
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destination)) {
                echo "La imagen se subió correctamente. <br>";
                echo "Ruta del archivo: <a href='$destination'>$destination</a>";
            } else {
                echo "Error: No se pudo mover el archivo. Revisa los permisos del directorio.<br>";
                error_log("Error al mover el archivo desde $fileTmpPath a $destination");
            }
        } else {
            echo "Error: Extensión no permitida. Solo se aceptan: " . implode(', ', $allowedExtensions);
        }
    } else {
        echo "Error: ";
        switch ($_FILES['image']['error']) {
            case UPLOAD_ERR_INI_SIZE:
                echo "El archivo excede el tamaño permitido por `upload_max_filesize`.";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                echo "El archivo excede el tamaño máximo permitido por el formulario.";
                break;
            case UPLOAD_ERR_PARTIAL:
                echo "El archivo solo se subió parcialmente.";
                break;
            case UPLOAD_ERR_NO_FILE:
                echo "No se subió ningún archivo.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                echo "Falta un directorio temporal.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                echo "No se pudo escribir el archivo en el disco.";
                break;
            case UPLOAD_ERR_EXTENSION:
                echo "Una extensión de PHP detuvo la subida.";
                break;
            default:
                echo "Error desconocido.";
                break;
        }
    }
} else {
    echo "Método no permitido.";
}
?>
