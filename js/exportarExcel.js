

function exportTablaToExcel(filen, Tabla) {

    $(Tabla).table2excel({
      //exclude: ".excludeThisClass",
      name: "Productos",
      filename: filen,
      fileext: ".xlsx",
      preserveColors: true
    })

  }