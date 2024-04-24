$(document).ready(function() {
  // Esconde todos los submenús inicialmente
  // $("#stic-menu .dropdown-submenu .dropdown-menu").hide();

  // // Gestiona el evento hover para mostrar solo el submenú correspondiente
  // $("#stic-menu .dropdown-submenu").hover(
  //   function() {
  //     // Muestra solo el submenú del ítem actual
  //     $(this).children(".dropdown-menu").stop(true, true).fadeIn(300);
  //   },
  //   function() {
  //     // Oculta el submenú cuando el ratón deja el ítem
  //     $(this).children(".dropdown-menu").stop(true, true).fadeOut(300);
  //   }
  // );
  $("#main-menu").smartmenus({
    subMenusSubOffsetX: 1,
    subMenusSubOffsetY: -8
  });
});
