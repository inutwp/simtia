!function(e){var t=[];function i(){t=e.grep(t,function(e){return e.length&&e.data("edatagrid")})}function d(d){i(),e.map(t,function(t){t[0]!=e(d)[0]&&t.edatagrid("saveRow")}),i()}function a(d){i(),t=e.grep(t,function(t){return e(t)[0]!=e(d)[0]})}function n(d){var n=e.data(d,"edatagrid").options;e(d).datagrid(e.extend({},n,{onDblClickCell:function(t,i,a){n.editing&&(e(this).edatagrid("editRow",t),r(d,i)),n.onDblClickCell&&n.onDblClickCell.call(d,t,i,a)},onClickCell:function(t,i,a){if(n.editIndex>=0){var o=e(this);n.editing?o.edatagrid("editRow",t):setTimeout(function(){o.edatagrid("selectRow",n.editIndex)},0),r(d,i)}n.onClickCell&&n.onClickCell.call(d,t,i,a)},onBeforeEdit:function(a,r){if(n.onBeforeEdit&&0==n.onBeforeEdit.call(d,a,r))return!1;n.autoSave&&function(d){i();for(var a=0;a<t.length;a++)if(e(t[a])[0]==e(d)[0])return;t.push(e(d))}(this),n.originalRow=e.extend(!0,[],r)},onAfterEdit:function(t,i){a(this),n.editIndex=-1;var r=i.isNewRecord?n.saveUrl:n.updateUrl;if(r){for(var o=!1,l=e(this).edatagrid("getColumnFields",!0).concat(e(this).edatagrid("getColumnFields")),s=0;s<l.length;s++){var c=l[s];if(e(this).edatagrid("getColumnOption",c).editor&&n.originalRow[c]!=i[c]){o=!0;break}}o?n.poster.call(d,r,i,function(a){if(a.isError){var r=n.originalRow;return e(d).edatagrid("cancelRow",t),e(d).edatagrid("selectRow",t),e(d).edatagrid("editRow",t),n.originalRow=r,void n.onError.call(d,t,a)}if(a.isNewRecord=null,e(d).datagrid("updateRow",{index:t,row:a}),n.tree){var o=i[n.idField||"id"],l=e(n.tree),s=l.tree("find",o);if(s)s.text=i[n.treeTextField],l.tree("update",s);else{var c=l.tree("find",i[n.treeParentField]);l.tree("append",{parent:c?c.target:null,data:[{id:o,text:i[n.treeTextField]}]})}}n.onSuccess.call(d,t,i),n.onSave.call(d,t,i)},function(e){n.onError.call(d,t,e)}):n.onSave.call(d,t,i)}else i.isNewRecord=!1,n.onSave.call(d,t,i);n.onAfterEdit&&n.onAfterEdit.call(d,t,i)},onCancelEdit:function(t,i){a(this),n.editIndex=-1,i.isNewRecord&&e(this).datagrid("deleteRow",t),n.onCancelEdit&&n.onCancelEdit.call(d,t,i)},onBeforeLoad:function(t){if(0==n.onBeforeLoad.call(d,t))return!1;if(e(this).edatagrid("cancelRow"),n.tree){var i=e(n.tree).tree("getSelected");t[n.treeParentField]=i?i.id:void 0}}})),n.tree&&e(n.tree).tree({url:n.treeUrl,onClick:function(t){e(d).datagrid("load")},onDrop:function(t,i,a){var r=e(this).tree("getNode",t).id,o={id:i.id,targetId:r,point:a};n.poster.call(d,n.treeDndUrl,o,function(t){e(d).datagrid("load")})}})}function r(t,i){var d,a=e(t).edatagrid("options"),n=e(t).datagrid("getEditor",{index:a.editIndex,field:i});if(n)d=n.target;else{var r=e(t).datagrid("getEditors",a.editIndex);r.length&&(d=r[0].target)}if(d){var o=e(t).data("datagrid"),l=o.dc.body2._scrollLeft();e(d).hasClass("textbox-f")?e(d).textbox("textbox").focus():e(d).focus(),o.dc.body2._scrollLeft(l)}}e(function(){e(document).unbind(".edatagrid").bind("mousedown.edatagrid",function(t){var i=e(t.target).closest("div.datagrid-view,div.combo-panel,div.window,div.window-mask");i.length?i.hasClass("datagrid-view")&&d(i.children("table")):d()})}),e.fn.edatagrid=function(t,i){if("string"==typeof t){var d=e.fn.edatagrid.methods[t];return d?d(this,i):this.datagrid(t,i)}return t=t||{},this.each(function(){var i=e.data(this,"edatagrid");i?e.extend(i.options,t):e.data(this,"edatagrid",{options:e.extend({},e.fn.edatagrid.defaults,e.fn.edatagrid.parseOptions(this),t)}),n(this)})},e.fn.edatagrid.parseOptions=function(t){return e.extend({},e.fn.datagrid.parseOptions(t),{})},e.fn.edatagrid.methods={options:function(t){return e.data(t[0],"edatagrid").options},loadData:function(t,i){return t.each(function(){e(this).edatagrid("cancelRow"),e(this).datagrid("loadData",i)})},enableEditing:function(t){return t.each(function(){e.data(this,"edatagrid").options.editing=!0})},disableEditing:function(t){return t.each(function(){e.data(this,"edatagrid").options.editing=!1})},isEditing:function(t,i){var d=e.data(t[0],"edatagrid").options.finder.getTr(t[0],i);return d.length&&d.hasClass("datagrid-row-editing")},editRow:function(t,i){return t.each(function(){var t=e(this),d=e.data(this,"edatagrid").options,a=d.editIndex;if(a!=i)if(t.datagrid("validateRow",a)){if(a>=0&&0==d.onBeforeSave.call(this,a))return void setTimeout(function(){t.datagrid("selectRow",a)},0);if(t.datagrid("endEdit",a),t.datagrid("beginEdit",i),!t.edatagrid("isEditing",i))return;d.editIndex=i,r(this);var n=t.datagrid("getRows");d.onEdit.call(this,i,n[i])}else setTimeout(function(){t.datagrid("selectRow",a)},0)})},addRow:function(t,i){return t.each(function(){var t=e(this),d=e.data(this,"edatagrid").options;if(d.editIndex>=0){if(!t.datagrid("validateRow",d.editIndex))return void t.datagrid("selectRow",d.editIndex);if(0==d.onBeforeSave.call(this,d.editIndex))return void setTimeout(function(){t.datagrid("selectRow",d.editIndex)},0);t.datagrid("endEdit",d.editIndex)}function a(e,i){null==e?(t.datagrid("appendRow",i),d.editIndex=t.datagrid("getRows").length-1):(t.datagrid("insertRow",{index:e,row:i}),d.editIndex=e)}"object"==typeof i?a(i.index,e.extend(i.row,{isNewRecord:!0})):a(i,{isNewRecord:!0}),t.datagrid("beginEdit",d.editIndex),t.datagrid("selectRow",d.editIndex);var n=t.datagrid("getRows");if(d.tree){var r=e(d.tree).tree("getSelected");n[d.editIndex][d.treeParentField]=r?r.id:0}d.onAdd.call(this,d.editIndex,n[d.editIndex])})},saveRow:function(t){return t.each(function(){var t=e(this),i=e.data(this,"edatagrid").options;if(i.editIndex>=0){if(0==i.onBeforeSave.call(this,i.editIndex))return void setTimeout(function(){t.datagrid("selectRow",i.editIndex)},0);e(this).datagrid("endEdit",i.editIndex)}})},cancelRow:function(t){return t.each(function(){var t=e.data(this,"edatagrid").options;t.editIndex>=0&&e(this).datagrid("cancelEdit",t.editIndex)})},destroyRow:function(t,i){return t.each(function(){var t=e(this),d=e.data(this,"edatagrid").options,a=[];if(null==i)a=t.datagrid("getSelections");else for(var n=e.isArray(i)?i:[i],r=0;r<n.length;r++){var o=d.finder.getRow(this,n[r]);o&&a.push(o)}function l(i){var a=t.datagrid("getRowIndex",i);if(-1!=a)if(i.isNewRecord)t.datagrid("cancelEdit",a);else if(d.destroyUrl){var n=i[d.idField||"id"];d.poster.call(t[0],d.destroyUrl,{id:n},function(a){var r=t.datagrid("getRowIndex",n);if(a.isError)return t.datagrid("selectRow",r),void d.onError.call(t[0],r,a);if(d.tree){t.datagrid("reload");var o=e(d.tree),l=o.tree("find",n);l&&o.tree("remove",l.target)}else t.datagrid("cancelEdit",r),t.datagrid("deleteRow",r);d.onDestroy.call(t[0],r,e.extend(i,a));var s=t.datagrid("getPager");s.length&&!t.datagrid("getRows").length&&(t.datagrid("options").pageNumber=s.pagination("options").pageNumber,t.datagrid("reload"))},function(e){d.onError.call(t[0],a,e)})}else t.datagrid("cancelEdit",a),t.datagrid("deleteRow",a),d.onDestroy.call(t[0],a,i)}a.length?e.messager.confirm(d.destroyMsg.confirm.title,d.destroyMsg.confirm.msg,function(e){if(e){for(var i=0;i<a.length;i++)l(a[i]);t.datagrid("clearSelections")}}):e.messager.show({title:d.destroyMsg.norecord.title,msg:d.destroyMsg.norecord.msg})})}},e.fn.edatagrid.defaults=e.extend({},e.fn.datagrid.defaults,{singleSelect:!0,editing:!0,editIndex:-1,destroyMsg:{norecord:{title:"Peringatan",msg:"Tidak ada data yang dipilih"},confirm:{title:"Konfirmasi",msg:"Anda yakin akan menghapus baris terpilih?"}},poster:function(t,i,d,a){e.ajax({type:"post",url:t,data:i,dataType:"json",success:function(e){d(e)},error:function(e,t,i){a({jqXHR:e,textStatus:t,errorThrown:i})}})},autoSave:!1,url:null,saveUrl:null,updateUrl:null,destroyUrl:null,tree:null,treeUrl:null,treeDndUrl:null,treeTextField:"name",treeParentField:"parentId",onAdd:function(e,t){},onEdit:function(e,t){},onBeforeSave:function(e){},onSave:function(e,t){},onSuccess:function(e,t){},onDestroy:function(e,t){},onError:function(e,t){}}),e.parser.plugins.push("edatagrid")}(jQuery);