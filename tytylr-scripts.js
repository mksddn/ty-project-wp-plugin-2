window.addEventListener("load", (event) => {

  // create script
  const playerInitCode = document.createElement("script");
  playerInitCode.defer = true;
  playerInitCode.id = typp_id;
  playerInitCode.innerHTML = `Widget.init("${typp_id}");`;

  // insert player
  const allParagraphs = document.querySelectorAll('.entry-content p');
  const theTitle = document.querySelector('.wp-block-post-title') || document.querySelector('.entry-title') || document.querySelector('h1')
  switch (typp_position) {
    case 'After 1st Paragraph':
      allParagraphs[0].appendChild(playerInitCode);
      break;
    case 'After 2nd Paragraph':
      allParagraphs[1].appendChild(playerInitCode);
      break;
    case 'After Content':
      allParagraphs[allParagraphs.length - 1].appendChild(playerInitCode);
      break;
    case 'Before Content':
      allParagraphs[0].prepend(playerInitCode);
      break;
    case 'After Title':
      theTitle.appendChild(playerInitCode);
      break;
    default:
      allParagraphs[0].prepend(playerInitCode);
      break;
  }

  // stylize static widget
  function stylizeStaticPlayer() {
    if (document.querySelector('#ty-project-widget')) {
      if (typp_type === 'static') {
        document.querySelector('#ty-project-widget').style.margin = '1rem 0';
      }
    } else {
      setTimeout(stylizeStaticPlayer, 15);
    }
  }
  stylizeStaticPlayer();

});