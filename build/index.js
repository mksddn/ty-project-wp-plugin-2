!function(){"use strict";var e=window.wp.element,t=window.wp.components,n=window.wp.data,l=window.wp.coreData,a=window.wp.editPost;(0,window.wp.plugins.registerPlugin)("typp-sidebar",{render:function(){const[o]=(0,l.useEntityProp)("root","site","typp_token"),[i,r]=(0,e.useState)([]),c=()=>{fetch("https://ty.mailstone.net/api/players",{method:"GET",status:"active",headers:{Authorization:o}}).then((e=>e.json())).then((e=>{r(e.filter((e=>"active"==e.status)).map((e=>({label:e.name,value:e.id,type:e.type}))))})).catch((e=>{})).finally((e=>{}))};(0,e.useEffect)((()=>{c()}),[]);const p=[{value:"",label:"Select a Player"}],s=(0,n.useSelect)((e=>e("core/editor").getCurrentPostType()),[]),[u,y]=(0,l.useEntityProp)("postType",s,"meta"),m=u.typp_name,d=u.typp_id,w=e=>{y({...u,typp_id:e,typp_name:i.filter((t=>t.value==e))[0].label,typp_type:i.filter((t=>t.value==e))[0].type})},P=u.typp_position;return(0,e.createElement)(a.PluginDocumentSettingPanel,{name:"customMetaPanel",title:"TY Project Player"},(0,e.createElement)("p",null,(0,e.createElement)("b",null,"Selected Player: ",(0,e.createElement)("i",null,m))),(0,e.createElement)("br",null),(0,e.createElement)(t.SelectControl,{onClick:c,label:"Select a Static Player",value:d,options:p.concat(i.filter((e=>"static"==e.type))),onChange:w}),(0,e.createElement)("br",null),(0,e.createElement)(t.SelectControl,{onClick:c,label:"Select a Dynamic Player",value:d,options:p.concat(i.filter((e=>"dynamic"==e.type))),onChange:w}),(0,e.createElement)("br",null),(0,e.createElement)(t.SelectControl,{label:"Select a Player Position",value:P,options:[{label:"Before Content",value:"Before Content"},{label:"After Content",value:"After Content"}],onChange:e=>{y({...u,typp_position:e})}}))},icon:""})}();