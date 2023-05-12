<?php
add_action('quick_edit_custom_box',  'typp_quick_edit_fields', 10, 2);

function typp_quick_edit_fields($column_name, $post_type)
{
  switch ($column_name) {
    case 'typp_name': {
?>
        <script>
          async function getPlayers() {
            let playerOptions = [];
            let typp_token = '<?php echo get_option('typp_token') ?>';
            await fetch("https://ty.mailstone.net/api/players", {
                method: "GET",
                status: "active",
                headers: {
                  Authorization: typp_token,
                },
              })
              .then((response) => {
                // console.log(response.ok);
                return response.json();
              })
              .then((data) => {
                playerOptions = data
                  .filter((player) => player.status == "active")
                  .map((player) => ({
                    label: player.name,
                    value: player.id,
                    type: player.type,
                  }));
                // console.log(playerOptions);
              })
              .catch((err) => {
                // renderErrorMessage(err);
              })
              .finally((data) => {
                const playerSelectors = document.querySelectorAll('.typp_id_selector');
                playerSelectors.forEach(selector => {
                  // console.log(playerOptions);
                  if (!selector.options.length) {
                    playerOptions.forEach(option => {
                      // console.log(name.label);
                      let opt = document.createElement('option');
                      opt.value = option.value;
                      // opt.option = option.label;
                      opt.text = option.label;
                      opt.setAttribute('data-type', option.type);
                      selector.add(opt);
                    });
                  }
                });
              });
          };
          getPlayers();

          function setNewPlayer() {
            const select = event.target;
            const newPlayerName = select.options[select.selectedIndex].text;
            const typpNameInput = select.parentNode.querySelector('.typp_name_selector');
            typpNameInput.value = newPlayerName;
            const newPlayerType = select.options[select.selectedIndex].getAttribute('data-type');
            const typpTypeInput = select.parentNode.querySelector('.typp_type_selector');
            typpTypeInput.value = newPlayerType;
          }
        </script>
        <fieldset class="inline-edit-col-left" style="width:auto;">
          <div class="inline-edit-col">
            <label for="typp_id">TY Project Player</label>
            <select name="typp_id" class="typp_id_selector" onchange="setNewPlayer();">
            </select>
            <input type="hidden" name="typp_name" class="typp_name_selector">
            <input type="hidden" name="typp_type" class="typp_type_selector">
          </div>
        <?php
        break;
      }
    case 'typp_position': {
        ?>
          <div class="inline-edit-col">
            <label for="typp_position">Player Position</label>
            <select name="typp_position" class="typp_position_selector">
              <option value="Before Content">Before Content</option>
              <!-- <option value="After 1st Paragraph">After 1st Paragraph</option> -->
              <option value="After Content">After Content</option>
            </select>
          </div>
        </fieldset>
  <?php
        break;
      }
  }
}

add_action('save_post', 'typp_quick_edit_save');
function typp_quick_edit_save($post_id)
{
  if (!wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce')) {
    return;
  }
  $typp_name = $_REQUEST['typp_name'] ?? get_post_meta($post_id, 'typp_name', true);
  update_post_meta($post_id, 'typp_name', $typp_name);
  $typp_id = $_REQUEST['typp_id'] ?? get_post_meta($post_id, 'typp_id', true);
  update_post_meta($post_id, 'typp_id', $typp_id);
  $typp_position = $_REQUEST['typp_position'] ?? get_post_meta($post_id, 'typp_position', true);
  update_post_meta($post_id, 'typp_position', $typp_position);
  $typp_type = $_REQUEST['typp_type'] ?? get_post_meta($post_id, 'typp_type', true);
  update_post_meta($post_id, 'typp_type', $typp_type);
}

add_action('admin_footer', 'typp_admin_footer_action');
function typp_admin_footer_action($data)
{ ?>
  <script>
    jQuery(function($) {
      if (window.location.href.indexOf("edit.php") > -1) {
        const wp_inline_edit_function = inlineEditPost.edit;
        inlineEditPost.edit = function(post_id) {
          wp_inline_edit_function.apply(this, arguments);
          if (typeof(post_id) == 'object') {
            post_id = parseInt(this.getId(post_id));
          }
          const edit_row = $('#edit-' + post_id)
          const post_row = $('#post-' + post_id)
          const typp_name = $('.column-typp_name', post_row).text();
          const typp_id = $('.column-typp_id', post_row).text();
          const typp_position = $('.column-typp_position', post_row).text();
          $(':input[name="typp_id"]', edit_row).val(typp_id);
          $(':input[name="typp_name"]', edit_row).val(typp_name);
          $(':input[name="typp_position"]', edit_row).val(typp_position);
        }
      }
    });
  </script>
<?php
}

add_action('bulk_edit_custom_box',  'typp_quick_edit_fields', 10, 2);

add_action('save_post', 'typp_bulk_edit_save');
function typp_bulk_edit_save($post_id)
{
  if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'bulk-posts')) {
    return;
  }
  $typp_name = !empty($_REQUEST['typp_name']) ? $_REQUEST['typp_name'] : get_post_meta($post_id, 'typp_name', true);
  update_post_meta($post_id, 'typp_name', $typp_name);
  $typp_id = !empty($_REQUEST['typp_id']) ? $_REQUEST['typp_id'] : get_post_meta($post_id, 'typp_id', true);
  update_post_meta($post_id, 'typp_id', $typp_id);
  $typp_position = !empty($_REQUEST['typp_position']) ? $_REQUEST['typp_position'] : get_post_meta($post_id, 'typp_position', true);
  update_post_meta($post_id, 'typp_position', $typp_position);
}
