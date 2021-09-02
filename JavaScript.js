var clicco = null;

function ordina(arr) {
  var counts = {};
  arr.forEach(function (value) {
    if (!counts[value]) {
      counts[value] = 0;
    }
    counts[value]++;
  });
  return Object.keys(counts).sort(function (curKey, nextKey) {
    return counts[curKey] < counts[nextKey];
  });
}

function webcam() {
  camera = document.getElementById("#camera");
  x = navigator.mediaDevices.getUserMedia({
    audio: false,
    video: {facingMode: {exact: "environment"}, width: 420, height: 1000},
  });
  if (
    navigator.mediaDevices &&
    typeof navigator.mediaDevices.getUserMedia === "function"
  ) {
    var last_result = [];
    if (Quagga.initialized == undefined) {
      Quagga.onDetected(function (result) {
        var last_code = result.codeResult.code;
        var format = result.codeResult.format;
        last_result.push(last_code);
        if (last_result.length > 20) {
          code = ordina(last_result)[0];
          r = last_result[0];
          console.log(r);

          document.querySelector("#result").value = r;
          last_result = [];
        }
      });
    }
    if (clicco == null) {
      clicco = 1;
      Quagga.init(
        {
          inputStream: {
            name: "Live",
            type: "LiveStream",
            constraints: {
              width: "790",
              height: "490",
            },
            numOfWorkers: navigator.hardwareConcurrency,
            target: document.querySelector("#camera"),
          },
          locate: true,

          decoder: {
            readers: [
              "code_128_reader",
              "upc_reader",
              "upc_e_reader",
              "ean_reader",
            ],
          },
        },
        function (err) {
          if (err) {
            console.log(err);
            return;
          }
          Quagga.initialized = true;
          Quagga.start();
        }
      );
    } else {
      Quagga.stop();
      clicco = null;
    }
  }
}

function displayMODIFICA(id) {
  //funzione per far apparire o nascondere un elemento con id
  if (document.getElementById(id).style.display == "block") {
    document.getElementById(id).style.display = "none";
  } else {
    document.getElementById(id).style.display = "block";
  }
}

function sostituisci(stringa, charOLD, charNEW) {
  //funzione per sostituire i caratteri di una stringa con altri

  var arr = stringa.split(charOLD);
  link = "";
  for (let index = 0; index < arr.length; index++) {
    link += arr[index] + charNEW;
  }
  return link;
}

function displayLISTA(codice, testo, tipo) {
  var amazon = sostituisci(testo, " ", "+");
  var result = "errore";
  if (tipo == "ESTERNO") {
    result =
      '<div class="white-text link titolocard small-text"' +
      'style="margin-right:20px;margin-left:20px;' +
      'padding: 10px 7px 10px 7px; display:flex; justify-content:space-between" >' +
      "<div>" +
      '<span class="code">' +
      "ESTERNO " +
      codice +
      "- </span>" +
      testo +
      " " +
      "</div>" +
      '<a class=link target="_blank" href=https://www.amazon.it/s?k=' +
      amazon +
      " " +
      '><span class="material-icons">shopping_cart</span></a>' +
      "</div>" +
      "<br>";
  } else if (tipo == "INTERNO_codice") {
    result =
      '<div class="white-text link titolocard small-text"' +
      'style="margin-right:20px;margin-left:20px;' +
      'padding: 10px 7px 10px 7px; display:flex; justify-content:space-between" >' +
      "<div>" +
      '<span class="code">' +
      codice +
      "- </span>" +
      testo +
      " " +
      "</div>" +
      '<a class=link target="_blank" href=https://www.amazon.it/s?k=' +
      amazon +
      " " +
      '><span class="material-icons">shopping_cart</span></a>' +
      "</div>" +
      "<br>";
  } else if (tipo == "INTERNO_nome") {
    result =
      '<div class="white-text link titolocard small-text" style="margin-right:20px;margin-left:20px; display:flex; justify-content:space-between; padding: 10px 7px 10px 7px">' +
      "<div>" +
      '<span class="code"> ' +
      codice +
      "- </span>" +
      testo +
      "</div>" +
      "<a href=/HOME/prodotti.php?prodotto=" +
      amazon +
      " " +
      '><span class="material-icons">info</span></a>' +
      "</div>" +
      "<br>";
  } else if (tipo == "ERRORE") {
    result =
      '<div class="white-text link titolocard small-text" style="margin-right:20px;margin-left:20px; padding: 10px 7px 10px 7px">' +
      '<span class="code">' +
      codice +
      "</span>" +
      " " +
      "nessun prodotto trovato " +
      "<a href=Login/Login.php>" +
      "aggiungilo" +
      "</a>" +
      "</div>";
  } else if (tipo == "TODOLIST") {
    result =
      '<div class="white-text link titolocard small-text"' +
      'style="margin-right:20px;margin-left:20px;' +
      'padding: 10px 7px 10px 7px; display:flex; justify-content:space-between" >' +
      '<div class="code">' +
      testo +
      "</div> " +
      "<a href=ToDoList.php?add=" +
      amazon +
      ">" +
      '<div class="material-icons buttonBase">' +
      "add_circle" +
      "</div>" +
      "</a>" +
      "</div>" +
      "<br>";
  } else if (tipo == "CONTEINER") {
    result =
      '<div class="white-text link titolocard small-text"' +
      'style="margin-right:20px;margin-left:20px;' +
      'padding: 10px 7px 10px 7px; display:flex; justify-content:space-between" >' +
      '<div class="code">' +
      codice +
      " -- " +
      testo +
      "</div> " +
      "<a href=Conteiner.php?add=" +
      amazon +
      ">" +
      '<div class="material-icons buttonBase">' +
      "add_circle" +
      "</div>" +
      "</a>" +
      "</div>" +
      "<br>";
  }

  return result;
}

async function img() {
  displayMODIFICA("INFO");
  Quagga.decodeSingle(
    {
      decoder: {
        readers: [
          "code_128_reader",
          "upc_reader",
          "upc_e_reader",
          "ean_reader",
          "ean_8_reader",
          "codabar_reader",
          "code_39_reader",
        ], // List of active readers
      },
      locate: true, // try to locate the barcode in the image
      src: URL.createObjectURL(this.files[0]), // or 'data:image/jpg;base64,' + data
    },
    //funzione che viene eseguita quando trova il codice
    async function (result) {
      if (result.codeResult) {
        var trovato = false;
        //stampo il risultato nel input text
        document.querySelector("#result").value = result.codeResult.code;

        //stampo il risultato nella lista

        //controllo se il prodotto è nel database esterno
        var lista = document.getElementById("lista");
        var api_url =
          "https://www.gtinsearch.org/api/items/" + result.codeResult.code;

        var response = await fetch(api_url);
        var data = await response.json();

        if (data[0] != null) {
          //ho trovato il risultato nel database esterno
          trovato = true;
          var li = document.createElement("li");
          li.innerHTML = displayLISTA(
            result.codeResult.code,
            data[0].name + " " + data[0].brand_name,
            "ESTERNO"
          );
          lista.appendChild(li);
        }

        //controllo se è nel mio
        var response = await fetch("/search.php", {
          method: "POST",
          body: new URLSearchParams(
            "nome=" + result.codeResult.code + "&tipo=codice"
          ),
        });
        var data = await response.json();
        if (data[0] != null) {
          //ho trovato il prodotto nel mio database
          trovato = true;
          var li = document.createElement("li");
          li.innerHTML = displayLISTA(
            result.codeResult.code,
            data[0]["nome"],
            "INTERNO_codice"
          );
          lista.appendChild(li);
        }
        if (trovato == false) {
          //non ho trovato il risultato in nessun database
          var li = document.createElement("li");
          li.innerHTML = displayLISTA(result.codeResult.code, "", "ERRORE");
          lista.appendChild(li);
        }
      } else {
        document.querySelector("#lista").innerHTML +=
          '<li class="normal-text white-text">' +
          "Codice Non Trovato" +
          "</li>";
      }
    }
  );
}

async function img_HOME() {
  Quagga.decodeSingle(
    {
      decoder: {
        readers: [
          "code_128_reader",
          "upc_reader",
          "upc_e_reader",
          "ean_reader",
          "ean_8_reader",
          "codabar_reader",
          "code_39_reader",
        ], // List of active readers
      },
      locate: true, // try to locate the barcode in the image
      src: URL.createObjectURL(this.files[0]), // or 'data:image/jpg;base64,' + data
    },
    async function (result) {
      if (result.codeResult) {
        //trovo il codice a barre
        document.getElementById("risultato").style.display = "block";
        document.getElementById("result").style.display = "block";
        document.getElementById("copy").style.display = "block";

        document.querySelector("#result").value = result.codeResult.code;
        search(result.codeResult.code, "misto");
      } else {
        //Non ho trovato nessun codice
        document.querySelector("#result").value = "Nessun Codice Trovato";
      }
    }
  );
}

async function search(nome, tipo) {
  var api_url = "https://www.gtinsearch.org/api/items/" + nome;
  if (tipo != "todolist" || tipo != "conteiner") {
    //controllo se è nel database esterno
    var response = await fetch(api_url);
    var data = await response.json();
    if (data[0] == null) {
      //se non c'è controllo se è nel mio
      response = fetch("/search.php", {
        method: "POST",
        body: new URLSearchParams("nome=" + nome + "&" + "tipo=" + tipo),
      })
        .then((res) => res.json())
        .then((res) => displayValori(res, tipo)); //funzione;
    } else {
      //se c'è preparo il link per amazon e poi la stampo
      var info = data[0].name + " " + data[0].brand_name;
      info = sostituisci(info, " ", "+");

      document.querySelector("#lista").innerHTML += displayLISTA(
        nome,
        data[0].name + " " + data[0].brand_name,
        "ESTERNO"
      );
    }
  } else {
    console.log(nome);
    response = fetch("/search.php", {
      method: "POST",
      body: new URLSearchParams("nome=" + nome + "&" + "tipo=" + tipo),
    })
      .then((res) => res.json())
      .then((res) => displayValori(res, tipo)); //funzione;
  }
}

function displayValori(data, tipo) {
  var Salvo = document.getElementById("lista").innerHTML;
  if (tipo == "codice") {
    var cerca = document.getElementById("lista");
    cerca.innerHTML = "";
    for (let i = 0; i < data.length; i++) {
      var li = document.createElement("li");
      li.innerHTML = displayLISTA(
        data[i]["codice_a_barre"],
        data[i]["nome"],
        "INTERNO_codice"
      );
      cerca.appendChild(li);
    }
    if (document.getElementById("boxCerca").value == "") {
      cerca.innerHTML = Salvo;
    }
  } else if (tipo == "misto") {
    var cerca = document.getElementById("lista");
    cerca.innerHTML = "";
    for (let i = 0; i < data.length; i++) {
      var li = document.createElement("li");
      li.innerHTML = displayLISTA(
        data[i]["codice_a_barre"],
        data[i]["nome"],
        "INTERNO_nome"
      );
      cerca.appendChild(li);
    }
    if (document.getElementById("boxCerca").value == "") {
      cerca.innerHTML = "";
    }
  } else if (tipo == "todolist") {
    var cerca = document.getElementById("lista");
    cerca.innerHTML = "";
    for (let i = 0; i < data.length; i++) {
      var li = document.createElement("li");
      li.innerHTML = displayLISTA("", data[i]["nome"], "TODOLIST");
      cerca.appendChild(li);
    }
  } else if (tipo == "conteiner") {
    var cerca = document.getElementById("lista");
    cerca.innerHTML = "";
    for (let i = 0; i < data.length; i++) {
      var li = document.createElement("li");
      li.innerHTML = displayLISTA(
        data[i]["codice_a_barre"],
        data[i]["nome"],
        "CONTEINER"
      );
      cerca.appendChild(li);
    }
  }
}

function cercatodolist(nome, tipo) {}

function COPY() {
  /* Get the text field */
  var copyText = document.getElementById("result");

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */

  /* Copy the text inside the text field */
  document.execCommand("copy");

  /* Alert the copied text */
}

function setHandlersINDEX() {
  c = document.getElementById("copy");
  c.addEventListener("click", COPY, true);

  fileSelect = document.getElementById("img");
  fileElem = document.getElementById("fileElem");
  fileSelect.addEventListener(
    "click",
    function (e) {
      if (fileElem) {
        fileElem.click();
      }
      e.preventDefault(); // prevent navigation to "#"
    },
    false
  );
  fileElem.addEventListener("change", img, false);

  logo = document.getElementById("logo");
  file = document.getElementById("file");
  logo.addEventListener(
    "click",
    function (e) {
      if (file) {
        file.click();
      }
      e.preventDefault(); // prevent navigation to "#"
    },
    false
  );
  file.addEventListener("change", img, false);
}

function setHandlersHOME() {
  c = document.getElementById("copy");
  c.addEventListener("click", COPY, true);

  fileSelect = document.getElementById("img");
  fileElem = document.getElementById("fileElem");
  fileSelect.addEventListener(
    "click",
    function (e) {
      if (fileElem) {
        fileElem.click();
      }
      e.preventDefault(); // prevent navigation to "#"
    },
    false
  );
  fileElem.addEventListener("change", img_HOME, false);
}
