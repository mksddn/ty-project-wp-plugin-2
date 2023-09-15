import {
  SelectControl,
  Button,
  ButtonGroup,
  RadioControl,
} from "@wordpress/components";
import { useSelect } from "@wordpress/data";
import { useEntityProp } from "@wordpress/core-data";
import { PluginDocumentSettingPanel } from "@wordpress/edit-post";
import { registerPlugin } from "@wordpress/plugins";
import { useEffect, useState } from "@wordpress/element";

function CustomMetaPanel() {
  const postType = useSelect(
    (select) => select("core/editor").getCurrentPostType(),
    []
  );
  if (postType !== "post" && postType !== "page") {
    return null;
  }
  const [typp_token] = useEntityProp("root", "site", "tytylr_token");
  const [playersOptions, setPlayersOptions] = useState([]);
  const [chosenType, setChosenType] = useState([]);
  const getPlayers = () => {
    fetch("https://dashboard.tylr.com/api/players", {
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
      });
  };
  useEffect(() => {
    getPlayers();
  }, []);
  const titleOption = [{ value: "", label: "Select a Player" }];
  const [meta, setMeta] = useEntityProp("postType", postType, "meta");
  const playerName = meta.typp_name;
  const playerID = meta.typp_id;
  function updatePlayer(newValue) {
    if (
      playersOptions.filter((player) => player.value == newValue)[0].type ===
      "dynamic"
    ) {
      setMeta({
        ...meta,
        typp_id: newValue,
        typp_name: playersOptions.filter(
          (player) => player.value == newValue
        )[0].label,
        typp_type: playersOptions.filter(
          (player) => player.value == newValue
        )[0].type,
        typp_position: "",
      });
    } else {
      setMeta({
        ...meta,
        typp_id: newValue,
        typp_name: playersOptions.filter(
          (player) => player.value == newValue
        )[0].label,
        typp_type: playersOptions.filter(
          (player) => player.value == newValue
        )[0].type,
        typp_position: "Before Content",
      });
    }
  }
  const playerType = meta.typp_type || null;
  const playerPosition = meta.typp_position || null;
  const updatePlayerPosition = (newValue) => {
    setMeta({ ...meta, typp_position: newValue || "" });
  };
  function hideShowSelect(type) {
    setChosenType(type);
    getPlayers();
  }
  function removePlayer() {
    setMeta({
      ...meta,
      typp_id: null,
      typp_name: null,
      typp_type: null,
      typp_position: "",
    });
    setChosenType(null);
  }
  return (
    <PluginDocumentSettingPanel
      name="customMetaPanel"
      title="TYLR Player"
    >
      {playerName ? (
        <p>
          <b>
            Selected Player: <i>{playerName}</i>
          </b>
        </p>
      ) : null}
      <RadioControl
        selected={chosenType}
        options={[
          { label: "Add a Dynamic Player", value: "dynamic" },
          { label: "Add a Static Player", value: "static" },
        ]}
        onChange={(value) => hideShowSelect(value)}
      />
      {chosenType === "dynamic" ? (
        <SelectControl
          onClick={getPlayers}
          label="Select a Dynamic Player"
          className="typp-btn"
          value={playerID}
          options={titleOption.concat(
            playersOptions.filter((player) => player.type == "dynamic")
          )}
          onChange={updatePlayer}
        />
      ) : null}
      {chosenType === "static" ? (
        <SelectControl
          onClick={getPlayers}
          label="Select a Static Player"
          className="typp-btn"
          value={playerID}
          options={titleOption.concat(
            playersOptions.filter((player) => player.type == "static")
          )}
          onChange={updatePlayer}
        />
      ) : null}
      {playerType === "static" || chosenType === "static" ? (
        <SelectControl
          label="Select a Player Position"
          className="typp-btn"
          value={playerPosition}
          options={[
            { label: "After Title", value: "After Title" },
            { label: "Before Content", value: "Before Content" },
            { label: "After Content", value: "After Content" },
            { label: "After 1st Paragraph", value: "After 1st Paragraph" },
            { label: "After 2nd Paragraph", value: "After 2nd Paragraph" },
          ]}
          onChange={updatePlayerPosition}
        />
      ) : null}
      {playerName ? (
        <ButtonGroup>
          <Button
            variant="link"
            className="typp-btn typp-btn-remove"
            onClick={removePlayer}
          >
            Delete the Current Player
          </Button>
        </ButtonGroup>
      ) : null}
    </PluginDocumentSettingPanel>
  );
}

registerPlugin("typp-sidebar", {
  render: CustomMetaPanel,
  icon: "",
});
