$(document).ready(function () {
    $(document).ready(lieuCP);
    $("#villes").change(lieuCP);
    $("#idlieu").change(lieuData);

    function lieuCP() {
        let id = document.getElementById("villes").value;
        let lieux = document.getElementById("idlieu");
        let cp = document.getElementById("codePostal");

        fetch("/getLieuxByVille/" + id)
            .then(reponse => reponse.json())
            .then(data => {
                lieux.innerHTML = "";
                for (let lieu of data) {
                    lieux.innerHTML += "<option value='" + lieu.id + "'>" + lieu.nom_lieu + "</option>";
                }
                lieuData();
            })
            .catch(erreur => console.log(erreur));

        fetch("/getCodePostal/" + id)
            .then(reponse => reponse.json())
            .then(data => {
                cp.innerText = "Code Postal : " + data[0].code_postal;
            })
            .catch(erreur => console.log(erreur));
    }

    function lieuData() {
        let id = document.getElementById("idlieu").value;
        let rue = document.getElementById("rue");
        let latitude = document.getElementById("latitude");
        let longitude = document.getElementById("longitude");

        fetch("/getLieu/" + id)
            .then(reponse => reponse.json())
            .then(data => {
                rue.innerText = "Rue : " + data[0].rue;
                latitude.innerText = "Latitude : " + data[0].latitude;
                longitude.innerText = "Longitude : " + data[0].longitude;
            })
            .catch(erreur => console.log(erreur));
    }
});