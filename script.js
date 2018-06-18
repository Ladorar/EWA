

function addCart(d) {
    var x = document.getElementById("korb");
    var option = document.createElement("option");
    option.text = d.getAttribute("data-name");
    option.value = d.getAttribute("data-name");
    x.add(option);
}

function deleteAll() {
    var y = document.getElementById("korb");
    for ( var i = y.options.length - 1; i > -1 ; i--) {
        y.options[i].remove();
    }
}

function bestellen() {
    var x = document.getElementById("form1");
    var y = document.getElementById("korb");
    for ( var i = 0; i < y.options.length; i++) {
        y.options[i].selected = true;
    }
    x.submit();
}