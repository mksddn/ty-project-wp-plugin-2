<?php
if (!defined('ABSPATH')) {
  exit;
}

add_action('quick_edit_custom_box',  'tytylr_quick_edit_fields', 10, 2);

function tytylr_quick_edit_fields($column_name, $post_type)
{
  switch ($column_name) {
    case 'typp_name': {
?>
        <script>
          async function getPlayers() {
            let playerOptions = [];
            let typp_token = '<?php echo esc_html(get_option('tytylr_token')); ?>';
            await fetch("https://dashboard.tylr.com/api/players", {
                method: "GET",
                status: "active",
                headers: {
                  Authorization: typp_token,
                },
              })
              .then((response) => {
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
              })
              .catch((err) => {
                // renderErrorMessage(err);
              })
              .finally((data) => {
                const playerSelectors = document.querySelectorAll('.typp_id_selector');
                playerSelectors.forEach(selector => {

                  if (selector.options.length < 2) {
                    function addOptgroup(groupName) {
                      const optgroupDynamic = document.createElement('optgroup');
                      optgroupDynamic.label = groupName;
                      selector.add(optgroupDynamic);
                    }

                    function populateOptions(groupType) {
                      playerOptions.forEach(option => {
                        if (option.type === groupType) {
                          let opt = document.createElement('option');
                          opt.value = option.value;
                          opt.text = option.label;
                          opt.setAttribute('data-type', option.type);
                          selector.add(opt);
                        }
                      });
                    }

                    addOptgroup('Dynamic Players');
                    populateOptions('dynamic');
                    addOptgroup('Static Players');
                    populateOptions('static');
                  }
                });
              });
          };
          getPlayers();

          function showHidePositionField(playerType, positionField) {
            if (playerType === 'static') {
              positionField.style.display = 'block';
            } else {
              positionField.style.display = 'none';
            }
          }

          function checkPositionFields() {
            document.querySelectorAll('.row-actions button.button-link.editinline').forEach(element => {
              element.addEventListener('click', function(e) {
                setTimeout(() => {
                  const row = e.target.closest('tr.iedit')
                  const playerType = row.querySelector('td.typp_type.column-typp_type').textContent;
                  const playerID = row.querySelector('td.typp_id.column-typp_id').textContent;
                  const positionField = document.querySelector(`option[value="${playerID}"]`).closest('fieldset').querySelector('div:has(label[for="typp_position"])');
                  showHidePositionField(playerType, positionField)
                }, 500);
              })
            });
          }
          checkPositionFields();

          function setNewPlayer() {
            const select = event.target;
            const newPlayerName = select.options[select.selectedIndex].text;
            const typpNameInput = select.parentNode.querySelector('.typp_name_selector');
            typpNameInput.value = newPlayerName;
            const newPlayerType = select.options[select.selectedIndex].getAttribute('data-type');
            const typpTypeInput = select.parentNode.querySelector('.typp_type_selector');
            typpTypeInput.value = newPlayerType;
            const positionField = select.parentNode.parentNode.querySelector('div:has(label[for="typp_position"])');
            showHidePositionField(newPlayerType, positionField);
          }

          function removePlayer() {
            const fieldset = event.target.closest('fieldset');
            fieldset.querySelectorAll('input, select').forEach(el => {
              el.value = '';
            });
            fieldset.querySelector('input.typp_type_remove').value = 'true';
            showHidePositionField('', fieldset.querySelector('div:has(label[for="typp_position"])'));
            event.target.innerHTML = `The current Player has been deleted<br>Don't forget to push Update!`;
          }
        </script>
        <fieldset class="inline-edit-col-left typp-quickedit-fieldset" style="width:auto;">
          <div class="inline-edit-col typp_id_selector-wrapper">
            <label for="typp_id">TYLR Player</label>
            <select name="typp_id" class="typp_id_selector" onchange="setNewPlayer();">
              <option disabled selected value> -- select an option -- </option>
            </select>
            <input type="hidden" name="typp_name" class="typp_name_selector">
            <input type="hidden" name="typp_type" class="typp_type_selector">
            <input type="hidden" name="typp_remove" class="typp_type_remove" value="false">
          </div>
        <?php
        break;
      }
    case 'typp_position': {
        ?>
          <div class="inline-edit-col typp_position_selector-wrapper">
            <label for="typp_position">Player Position</label>
            <select name="typp_position" class="typp_position_selector">
              <option disabled selected value> -- select an option -- </option>
              <option value="After Title">After Title</option>
              <option value="Before Content">Before Content</option>
              <option value="After Content">After Content</option>
              <option value="After 1st Paragraph">After 1st Paragraph</option>
              <option value="After 2nd Paragraph">After 2nd Paragraph</option>
            </select>
          </div>
          <div class="typp-btn-remove typp-quickedit-remove" onclick="removePlayer();">Delete the current Player</div>
        </fieldset>
  <?php
        break;
      }
  }
}

add_action('save_post', 'tytylr_quick_edit_save');
function tytylr_quick_edit_save($post_id)
{
  if (!isset($_POST['_inline_edit']) || !wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce')) {
    return;
  }
  $typp_name = sanitize_text_field($_REQUEST['typp_name']) ?? get_post_meta($post_id, 'typp_name', true);
  update_post_meta($post_id, 'typp_name', $typp_name);
  $typp_id = sanitize_text_field($_REQUEST['typp_id']) ?? get_post_meta($post_id, 'typp_id', true);
  update_post_meta($post_id, 'typp_id', $typp_id);
  $typp_type = sanitize_text_field($_REQUEST['typp_type']) ?? get_post_meta($post_id, 'typp_type', true);
  update_post_meta($post_id, 'typp_type', $typp_type);
  if ($typp_type == 'static') {
    $typp_position = isset($_REQUEST['typp_position']) ? sanitize_text_field($_REQUEST['typp_position']) : 'Before Content';
    update_post_meta($post_id, 'typp_position', $typp_position);
  } else {
    update_post_meta($post_id, 'typp_position', '');
  }
  if (sanitize_text_field($_REQUEST['typp_remove']) == 'true') {
    update_post_meta($post_id, 'typp_id', '');
  }
}

add_action('admin_footer', 'tytylr_admin_footer_action');
function tytylr_admin_footer_action($data)
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
          const typp_type = $('.column-typp_type', post_row).text();
          $(':input[name="typp_id"]', edit_row).val(typp_id);
          $(':input[name="typp_name"]', edit_row).val(typp_name);
          $(':input[name="typp_position"]', edit_row).val(typp_position);
          $(':input[name="typp_type"]', edit_row).val(typp_type);
        }
      }
    });
  </script>
<?php
}

add_action('bulk_edit_custom_box',  'tytylr_quick_edit_fields', 10, 2);

add_action('save_post', 'tytylr_bulk_edit_save');
function tytylr_bulk_edit_save($post_id)
{
  if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'bulk-posts')) {
    return;
  }
  $typp_name = sanitize_text_field($_REQUEST['typp_name']) ?? get_post_meta($post_id, 'typp_name', true);
  update_post_meta($post_id, 'typp_name', $typp_name);
  $typp_id = sanitize_text_field($_REQUEST['typp_id']) ?? get_post_meta($post_id, 'typp_id', true);
  update_post_meta($post_id, 'typp_id', $typp_id);
  $typp_type = sanitize_text_field($_REQUEST['typp_type']) ?? get_post_meta($post_id, 'typp_type', true);
  update_post_meta($post_id, 'typp_type', $typp_type);
  if ($typp_type == 'static') {
    $typp_position = isset($_REQUEST['typp_position']) ? sanitize_text_field($_REQUEST['typp_position']) : 'Before Content';
    update_post_meta($post_id, 'typp_position', $typp_position);
  } else {
    update_post_meta($post_id, 'typp_position', '');
  }
  if (sanitize_text_field($_REQUEST['typp_remove']) == 'true') {
    update_post_meta($post_id, 'typp_id', '');
  }
}
