$(document).ready(function () {
  // https://www.pierre-giraud.com/trier-tableau-javascript/
  /*VERSION FACTORISEE*/
  const compare = (ids, asc) => (row1, row2) => {
    const tdValue = (row, ids) => row.children[ids].textContent;
    const tri = (v1, v2) => v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2);
    return tri(tdValue(asc ? row1 : row2, ids), tdValue(asc ? row2 : row1, ids));
  };
  const tbody = document.querySelector('tbody');
  const thead = document.querySelector('thead');
  // const thx = document.querySelectorAll('th');
  const thx = thead.querySelectorAll('a');
  const trxb = tbody.querySelectorAll('tr');
  thx.forEach(th => th.addEventListener('click', () => {
    let classe = Array.from(trxb).sort(compare(Array.from(thx).indexOf(th), this.asc = !this.asc));
    classe.forEach(tr => tbody.appendChild(tr));
  }));
  /* ######################################################## */
  /* ####################   SEARCH BAR   #################### */
  // /* ######################################################## */
  //   document.getElementById('search-input').addEventListener('keyup', function(e) {
  //     var recherche = this.value.toLowerCase();
  //     table = document.getElementById("myTable");
  //     var tr = table.querySelectorAll('tr');
  //     Array.prototype.forEach.call(tr, function(document) {
  //       // On a bien trouvé les termes de recherche.
  //       if (document.innerHTML.toLowerCase().indexOf(recherche) > -1) {
  //         document.style.display = '';
  //       } else {
  //         document.style.display = 'none';
  //       }
  //       // if (recherche.value < 2) {
  //       //   document.style.display = '';
  //       // }
  //     });
  //   });
  
  // Permet de rendre responsive le tableau (dons)
  document.querySelectorAll('.table-respons').forEach(function (table) {
    let labels = Array.from(table.querySelectorAll('th')).map(function (th) {
      return th.innerText
    })
    table.querySelectorAll('td').forEach(function (td, i) {
      td.setAttribute('data-label', labels[i % labels.length])
    })
  });

});