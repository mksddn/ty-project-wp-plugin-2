import { PanelRow, TextControl, SelectControl } from "@wordpress/components";
import { useSelect } from "@wordpress/data";
import { useEntityProp } from "@wordpress/core-data";
import { PluginDocumentSettingPanel } from "@wordpress/edit-post";
import { registerPlugin } from "@wordpress/plugins";
import { useEffect, useState } from "@wordpress/element";

function CustomMetaPanel() {
  const [typp_token] = useEntityProp("root", "site", "typp_token");
  const [playersOptions, setPlayersOptions] = useState([]);
  const getPlayers = () => {
    fetch("https://ty.mailstone.net/api/players", {
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
        setPlayersOptions(
          data
            .filter((player) => player.status == "active")
            .map((player) => ({
              label: player.name,
              value: player.id,
              type: player.type,
            }))
        );
      })
      .catch((err) => {
        // renderErrorMessage(err);
      })
      .finally((data) => {
        // console.log("fetch finished");
        // setPlayersOptions(
        //   data.map((player) => ({
        //     label: player.name,
        //     value: player.id,
        //   }))
        // );
      });
  };
  useEffect(() => {
    getPlayers();
  }, []);
  const titleOption = [{ value: "", label: "Select a Player" }];

  const postType = useSelect(
    (select) => select("core/editor").getCurrentPostType(),
    []
  );
  const [meta, setMeta] = useEntityProp("postType", postType, "meta");
  const playerName = meta.typp_name;
  //   const updatePlayerName = (newLabel) => {
  //     setMeta({ ...meta, typp_name: newLabel });
  //   };
  const playerID = meta.typp_id;
  const updatePlayer = (newValue) => {
    setMeta({
      ...meta,
      typp_id: newValue,
      typp_name: playersOptions.filter((player) => player.value == newValue)[0]
        .label,
      typp_type: playersOptions.filter((player) => player.value == newValue)[0]
        .type,
    });
  };
  const playerPosition = meta.typp_position;
  const updatePlayerPosition = (newValue) => {
    setMeta({ ...meta, typp_position: newValue });
  };
  return (
    <PluginDocumentSettingPanel
      name="customMetaPanel"
      title="TY Project Player"
    >
      <p>
        <b>
          Selected Player: <i>{playerName}</i>
        </b>
      </p>
      <br />
      {/* <TextControl
        label="Selected Player"
        value={playerName}
        //   onChange={updatePlayerID}
      /> */}
      <SelectControl
        onClick={getPlayers}
        label="Select a Static Player"
        value={playerID}
        options={titleOption.concat(
          playersOptions.filter((player) => player.type == "static")
        )}
        onChange={updatePlayer}
      />
      <br />
      <SelectControl
        onClick={getPlayers}
        label="Select a Dynamic Player"
        value={playerID}
        options={titleOption.concat(
          playersOptions.filter((player) => player.type == "dynamic")
        )}
        onChange={updatePlayer}
      />
      <br />
      <SelectControl
        label="Select a Player Position"
        value={playerPosition}
        options={[
          { label: "Before Content", value: "Before Content" },
          // { label: "After 1st Paragraph", value: "After 1st Paragraph" },
          { label: "After Content", value: "After Content" },
        ]}
        onChange={updatePlayerPosition}
      />
    </PluginDocumentSettingPanel>
  );
}

registerPlugin("typp-sidebar", {
  render: CustomMetaPanel,
  icon: "",
});
