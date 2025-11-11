var d = window.location.pathname.split('/');

if (d[1] == 'dashboard') {
    $(".sidebar-menu ul.menu li.dashboard").addClass("active");
}
//master
if (d[1] == 'kebun') {
    $(".sidebar-menu ul.menu li.master").addClass("active");
    $("ul.submenu li.kebun").addClass("active");
    $("li.master ul").addClass("active");
}
if (d[1] == 'aset') {
    $(".sidebar-menu ul.menu li.master").addClass("active");
    $("ul.submenu li.aset").addClass("active");
    $("li.master ul").addClass("active");
}
if (d[1] == 'kategori') {
    $(".sidebar-menu ul.menu li.master").addClass("active");
    $("ul.submenu li.kategori").addClass("active");
    $("li.master ul").addClass("active");
}
if (d[1] == 'satuan') {
    $(".sidebar-menu ul.menu li.master").addClass("active");
    $("ul.submenu li.satuan").addClass("active");
    $("li.master ul").addClass("active");
}
if (d[1] == 'account') {
    $(".sidebar-menu ul.menu li.master").addClass("active");
    $("ul.submenu li.akun").addClass("active");
    $("li.master ul").addClass("active");
}
//track
if (d[1] == 'masuk') {
    $(".sidebar-menu ul.menu li.masuk").addClass("active");
}  
if (d[1] == 'keluar') {
    $(".sidebar-menu ul.menu li.keluar").addClass("active");
}
//hris
if (d[1] == 'karyawan') {
    $(".sidebar-menu ul.menu li.hr").addClass("active");
    $("ul.submenu li.karyawan").addClass("active");
    $("li.hr ul").addClass("active");
}
if (d[1] == 'salary') {
    $(".sidebar-menu ul.menu li.hr").addClass("active");
    $("ul.submenu li.salary").addClass("active");
    $("li.hr ul").addClass("active");
}
if (d[1] == 'absensi') {
    $(".sidebar-menu ul.menu li.hr").addClass("active");
    $("ul.submenu li.absensi").addClass("active");
    $("li.hr ul").addClass("active");
}
if (d[1] == 'panen') {
    $(".sidebar-menu ul.menu li.hr").addClass("active");
    $("ul.submenu li.panen").addClass("active");
    $("li.hr ul").addClass("active");
}
if (d[1] == 'dataabsensi') {
    $(".sidebar-menu ul.menu li.hr").addClass("active");
    $("ul.submenu li.absensi").addClass("active");
    $("li.hr ul").addClass("active");
}
if (d[1] == 'datapanen') {
    $(".sidebar-menu ul.menu li.hr").addClass("active");
    $("ul.submenu li.panen").addClass("active");
    $("li.hr ul").addClass("active");
}
//finance
if (d[1] == 'laba') {
    $(".sidebar-menu ul.menu li.laba").addClass("active");
}
if (d[1] == 'cost') {
    $(".sidebar-menu ul.menu li.cost").addClass("active");
}
if (d[1] == 'report') {
    $(".sidebar-menu ul.menu li.report").addClass("active");
}
//nav
str = d[1].toLowerCase().replace(/\b[a-z]/g, function(letter) {
    return letter.toUpperCase();
});
document.getElementById("page").innerHTML = str
if(d[1] === 'dashboard'){
    $("nav.breadcrumb-header").addClass("opacity-0");
}
window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
      $(this).remove(); 
    });
  }, 5000);
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })

  