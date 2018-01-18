var countCrit=1,
    countNut=1,
    limitCrit=5,
    limitNut=16;

function addCrite(){
    if (countCrit < limitCrit) {
        countCrit = addElem("firstcrit", "critlist", countCrit);
        if (countCrit == limitCrit) document.getElementById("addCrit").parentElement.removeChild(document.getElementById("addCrit"));
    }
}

function addNutr(){
    if (countNut < limitNut) {
        countNut = addElem("firstnut", "nutlist", countNut);
        if (countNut == limitNut) document.getElementById("addNut").parentElement.removeChild(document.getElementById("addNut"));
    }
}

function addElem(model, father, count){
    var elem = document.createElement('div');
    elem.innerHTML = document.getElementById(model).innerHTML;
    document.getElementById(father).appendChild(elem);
    count++;
    return count;
}

function addAdditives(){
    var list = document.getElementById("additivesselect");
    var elem = list.options[list.selectedIndex];

    if (elem.value != 'none') {
        var tmp = document.createElement("input");
        tmp.setAttribute("type", "text");
        tmp.setAttribute("name", "additives["+elem.value+"]");
        tmp.setAttribute("id", "additives["+elem.value+"]");
        tmp.setAttribute("value", elem.innerText);
        tmp.setAttribute("readonly", "readonly");

        document.getElementById("addiselected").appendChild(tmp);
        list.removeChild(elem);

        var tmp = document.createElement("input");
        tmp.setAttribute("type", "button");
        tmp.setAttribute("class", "adder");
        tmp.setAttribute("id", "additives["+elem.value+"]2");
        tmp.setAttribute("onclick", "removeAdditives('additives["+elem.value+"]')");
        tmp.setAttribute("value", "Retirer cet Additif");

        document.getElementById("addiselected").appendChild(tmp);
    }

    //moveto("additivesselect", "addiselected", "additives");
}

function removeAdditives(elemid){
    var elem = document.getElementById(elemid);

    if (elem.value != 'none') {
        var tmp = document.createElement("option");
        tmp.setAttribute("value", elem.value);
        tmp.innerText = elem.value;

        document.getElementById("additivesselect").appendChild(tmp);
        elem.parentElement.removeChild(document.getElementById(elemid+'2'))
        elem.parentElement.removeChild(elem);
    }

    //moveto("addiselected", "additivesselect", "");
}

function addCountries(){
    var list = document.getElementById("countiresselect");
    var elem = list.options[list.selectedIndex];

    if (elem.value != 'none') {
        var tmp = document.createElement("input");
        tmp.setAttribute("type", "text");
        tmp.setAttribute("name", "countries["+elem.value+"]");
        tmp.setAttribute("id", "countries["+elem.value+"]");
        tmp.setAttribute("value", elem.innerText);
        tmp.setAttribute("readonly", "readonly");

        document.getElementById("countrieselected").appendChild(tmp);
        list.removeChild(elem);

        var tmp = document.createElement("input");
        tmp.setAttribute("type", "button");
        tmp.setAttribute("class", "adder");
        tmp.setAttribute("id", "countries["+elem.value+"]2");
        tmp.setAttribute("onclick", "removeCountries('countries["+elem.value+"]')");
        tmp.setAttribute("value", "Retirer ce Pays");

        document.getElementById("countrieselected").appendChild(tmp);
    }
    //moveto("countiresselect", "countrieselected", "countries");
}

function removeCountries(elemid){
    var elem = document.getElementById(elemid);

    if (elem.value != 'none') {
        var tmp = document.createElement("option");
        tmp.setAttribute("value", elem.value);
        tmp.innerText = elem.value;

        document.getElementById("countiresselect").appendChild(tmp);
        elem.parentElement.removeChild(document.getElementById(elemid+'2'))
        elem.parentElement.removeChild(elem);
    }
    //moveto("countrieselected", "countiresselect", "");
}

function moveto(liste, other, names){
    var list = document.getElementById(liste);
    var elem = list.options[list.selectedIndex];

    if (elem.value != 'none') {
        if (names != ""){
            elem.setAttribute("name", names+"["+elem.value+"]");
        }
        document.getElementById(other).appendChild(elem);
        list.removeChild(elem);
    }
}