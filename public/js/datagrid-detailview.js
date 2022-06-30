$.extend($.fn.datagrid.defaults,{autoUpdateDetail:!0});var detailview=$.extend({},$.fn.datagrid.defaults.view,{type:"detailview",render:function(t,a,d){var i=$.data(t,"datagrid"),e=i.options;if(!d||e.rownumbers||e.frozenColumns&&e.frozenColumns.length){var r=i.data.rows,n=$(t).datagrid("getColumnFields",d),o=[];o.push('<table class="datagrid-btable" cellspacing="0" cellpadding="0" border="0"><tbody>');for(var s=0;s<r.length;s++){var l=e.rowStyler?e.rowStyler.call(t,s,r[s]):"",g="",c="";"string"==typeof l?c=l:l&&(g=l.class||"",c=l.style||"");var h='class="datagrid-row '+(s%2&&e.striped?"datagrid-row-alt ":" ")+g+'"',u=c?'style="'+c+'"':"",p=i.rowIdPrefix+"-"+(d?1:2)+"-"+s;o.push('<tr id="'+p+'" datagrid-row-index="'+s+'" '+h+" "+u+">"),o.push(this.renderRow.call(this,t,n,d,s,r[s])),o.push("</tr>"),o.push('<tr style="display:none;">'),d?o.push("<td colspan="+(n.length+(e.rownumbers?1:0))+' style="border-right:0">'):o.push("<td colspan="+n.length+">"),o.push('<div class="datagrid-row-detail">'),d?o.push("&nbsp;"):o.push(e.detailFormatter.call(t,s,r[s])),o.push("</div>"),o.push("</td>"),o.push("</tr>")}o.push("</tbody></table>"),$(a).html(o.join(""))}},renderRow:function(t,a,d,i,e){var r=$.data(t,"datagrid").options,n=[];if(d&&r.rownumbers){var o=i+1;r.pagination&&(o+=(r.pageNumber-1)*r.pageSize),n.push('<td class="datagrid-td-rownumber"><div class="datagrid-cell-rownumber">'+o+"</div></td>")}for(var s=0;s<a.length;s++){var l=a[s],g=$(t).datagrid("getColumnOption",l);if(g){var c=e[l],h=g.styler&&g.styler(c,e,i)||"",u="",p="";"string"==typeof h?p=h:n&&(u=h.class||"",p=h.style||"");var f=u?'class="'+u+'"':"",v=g.hidden?'style="display:none;'+p+'"':p?'style="'+p+'"':"";n.push('<td field="'+l+'" '+f+" "+v+">"),g.checkbox?v="":g.expander?v="text-align:center;height:16px;":(v=p,g.align&&(v+=";text-align:"+g.align+";"),r.nowrap?r.autoRowHeight&&(v+=";height:auto;"):v+=";white-space:normal;height:auto;"),n.push('<div style="'+v+'" '),g.checkbox?n.push('class="datagrid-cell-check '):n.push('class="datagrid-cell '+g.cellClass),n.push('">'),g.checkbox?n.push('<input type="checkbox" name="'+l+'" value="'+(null!=c?c:"")+'">'):g.expander?n.push('<span class="datagrid-row-expander datagrid-row-expand" style="display:inline-block;width:16px;height:16px;margin:0;cursor:pointer;" />'):g.formatter?n.push(g.formatter(c,e,i)):n.push(c),n.push("</div>"),n.push("</td>")}}return n.join("")},insertRow:function(t,a,d){var i=$.data(t,"datagrid").options,e=$.data(t,"datagrid").dc,r=($(t).datagrid("getPanel"),e.view1,e.view2,!1),n=$(t).datagrid("getRows").length;function o(e){var n=i.finder.getTr(t,a,"body",e?1:2);if(r){var o=n.next(),s=n.next().clone();n.insertAfter(o)}else s=n.next().next().clone();s.insertAfter(n),s.hide(),e||s.find("div.datagrid-row-detail").html(i.detailFormatter.call(t,a,d))}0!=n?((null==a||null==a||a>=n)&&(a=n,r=!0,this.canUpdateDetail=!1),$.fn.datagrid.defaults.view.insertRow.call(this,t,a,d),o(!0),o(!1),this.canUpdateDetail=!0):$(t).datagrid("loadData",{total:1,rows:[d]})},deleteRow:function(t,a){var d=$.data(t,"datagrid").options,i=$.data(t,"datagrid").dc;d.finder.getTr(t,a).next().remove(),$.fn.datagrid.defaults.view.deleteRow.call(this,t,a),i.body2.triggerHandler("scroll")},updateRow:function(t,a,d){$.data(t,"datagrid").dc;var i=$.data(t,"datagrid").options,e=$(t).datagrid("getExpander",a).attr("class");if($.fn.datagrid.defaults.view.updateRow.call(this,t,a,d),$(t).datagrid("getExpander",a).attr("class",e),i.autoUpdateDetail&&this.canUpdateDetail){d=$(t).datagrid("getRows")[a];$(t).datagrid("getRowDetail",a).html(i.detailFormatter.call(t,a,d))}},bindEvents:function(t){var a=$.data(t,"datagrid");if(!a.ss.bindDetailEvents){a.ss.bindDetailEvents=!0;var d=a.dc,i=(a.options,d.body1.add(d.body2));($.data(i[0],"events")||$._data(i[0],"events")).click[0].handler;i.unbind("click.detailview").bind("click.detailview",function(a){var d=$(a.target),i=d.closest("tr.datagrid-row");if(i.length&&d.hasClass("datagrid-row-expander")){var e=parseInt(i.attr("datagrid-row-index"));d.hasClass("datagrid-row-expand")?$(t).datagrid("expandRow",e):$(t).datagrid("collapseRow",e),$(t).datagrid("fixRowHeight"),a.stopPropagation()}})}},onBeforeRender:function(t){for(var a=$.data(t,"datagrid"),d=a.options,i=a.dc,e=!1,r=(o=$(t)).datagrid("getColumnFields",!0).concat(o.datagrid("getColumnFields")),n=0;n<r.length;n++){if(o.datagrid("getColumnOption",r[n]).expander){e=!0;break}}if(!e){d.frozenColumns&&d.frozenColumns.length?d.frozenColumns[0].splice(0,0,{field:"_expander",expander:!0,width:24,resizable:!1,fixed:!0}):d.frozenColumns=[[{field:"_expander",expander:!0,width:24,resizable:!1,fixed:!0}]];var o=i.view1.children("div.datagrid-header").find("table"),s=$('<td rowspan="'+d.frozenColumns.length+'"><div class="datagrid-header-expander" style="width:24px;"></div></td>');0==$("tr",o).length?s.wrap("<tr></tr>").parent().appendTo($("tbody",o)):d.rownumbers?s.insertAfter(o.find("td:has(div.datagrid-header-rownumber)")):s.prependTo(o.find("tr:first"))}},onAfterRender:function(t){var a=$.data(t,"datagrid"),d=a.dc,i=a.options,e=$(t).datagrid("getPanel");function r(){var t=d.body2.find(">table.datagrid-btable>tbody>tr>td>div.datagrid-row-detail:visible");if(t.length){var a=0;d.body2.find(">table.datagrid-btable>tbody>tr:visible:first").find(".datagrid-cell-check:visible,.datagrid-cell:visible").each(function(){a+=$(this).outerWidth(!0)+1}),a!=t.outerWidth(!0)&&(t._outerWidth(a),t.find(".easyui-fluid").trigger("_resize"))}}$.fn.datagrid.defaults.view.onAfterRender.call(this,t),a.onResizeColumn||(a.onResizeColumn=i.onResizeColumn,i.onResizeColumn=function(d,e){i.fitColumns||r();for(var n=$(t).datagrid("getRows").length,o=0;o<n;o++)$(t).datagrid("fixDetailRowHeight",o);a.onResizeColumn.call(t,d,e)}),a.onResize||(a.onResize=i.onResize,i.onResize=function(t,d){i.fitColumns&&r(),a.onResize.call(e,t,d)}),this.canUpdateDetail=!0,d.footer1.add(d.footer2).find("span.datagrid-row-expander").css("visibility","hidden"),$(t).datagrid("resize"),this.bindEvents(t),d.body1.add(d.body2).find("div.datagrid-row-detail").unbind().bind("mouseover mouseout click dblclick contextmenu scroll",function(t){t.stopPropagation()})}});$.extend($.fn.datagrid.methods,{fixDetailRowHeight:function(t,a){return t.each(function(){var t=$.data(this,"datagrid").options;if(t.rownumbers||t.frozenColumns&&t.frozenColumns.length){var d=$.data(this,"datagrid").dc,i=t.finder.getTr(this,a,"body",1).next(),e=t.finder.getTr(this,a,"body",2).next();if(e.is(":visible")){i.css("height",""),e.css("height","");var r=Math.max(i.height(),e.height());i.css("height",r),e.css("height",r)}d.body2.triggerHandler("scroll")}})},getExpander:function(t,a){return $.data(t[0],"datagrid").options.finder.getTr(t[0],a).find("span.datagrid-row-expander")},getRowDetail:function(t,a){return $.data(t[0],"datagrid").options.finder.getTr(t[0],a,"body",2).next().find(">td>div.datagrid-row-detail")},expandRow:function(t,a){return t.each(function(){var t=$(this).datagrid("options"),d=($.data(this,"datagrid").dc,$(this).datagrid("getExpander",a));if(d.hasClass("datagrid-row-expand")){d.removeClass("datagrid-row-expand").addClass("datagrid-row-collapse");var i=t.finder.getTr(this,a,"body",1).next(),e=t.finder.getTr(this,a,"body",2).next();if(i.show(),e.show(),$(this).datagrid("fixDetailRowHeight",a),t.onExpandRow){var r=$(this).datagrid("getRows")[a];t.onExpandRow.call(this,a,r)}}})},collapseRow:function(t,a){return t.each(function(){var t=$(this).datagrid("options"),d=$.data(this,"datagrid").dc,i=$(this).datagrid("getExpander",a);if(i.hasClass("datagrid-row-collapse")){i.removeClass("datagrid-row-collapse").addClass("datagrid-row-expand");var e=t.finder.getTr(this,a,"body",1).next(),r=t.finder.getTr(this,a,"body",2).next();if(e.hide(),r.hide(),d.body2.triggerHandler("scroll"),t.onCollapseRow){var n=$(this).datagrid("getRows")[a];t.onCollapseRow.call(this,a,n)}}})}}),$.extend($.fn.datagrid.methods,{subgrid:function(t,a){return t.each(function(){function t(t){var a=$(t).closest("div.datagrid-row-detail").closest("tr").prev();if(a.length){var i=parseInt(a.attr("datagrid-row-index"));d(a.closest("div.datagrid-view").children("table")[0],i)}}function d(t,a){$(t).datagrid("fixDetailRowHeight",a),$(t).datagrid("fixRowHeight",a);var i=$(t).closest("div.datagrid-row-detail").closest("tr").prev();if(i.length){a=parseInt(i.attr("datagrid-row-index"));d(i.closest("div.datagrid-view").children("table")[0],a)}}!function a(i,e,r){var n=$.extend({},e.options.queryParams||{});if(r){var o=e.options.foreignField;$.isFunction(o)?$.extend(n,o.call(e,r)):n[o]=r[o]}var s=e.options.edatagrid?"edatagrid":"datagrid";$(i)[s]($.extend({},e.options,{subgrid:e.subgrid,view:e.subgrid?detailview:void 0,queryParams:n,detailFormatter:function(t,a){return'<div><table class="datagrid-subgrid"></table></div>'},onExpandRow:function(t,i){var r,n=$(this).datagrid("options"),o=$(this).datagrid("getRowDetail",t),s=(r=$(o).children("div")).children("div.datagrid").length?r.find(">div.datagrid>div.panel-body>div.datagrid-view>table.datagrid-subgrid"):r.find(">table.datagrid-subgrid");s.data("datagrid")||a(s[0],n.subgrid,i),o.find(".easyui-fluid").trigger("_resize"),d(this,t),e.options.onExpandRow&&e.options.onExpandRow.call(this,t,i)},onCollapseRow:function(t,a){d(this,t),e.options.onCollapseRow&&e.options.onCollapseRow.call(this,t,a)},onResize:function(){$(this).children("div.datagrid-view").children("table");t(this)},onResizeColumn:function(a,d){t(this),e.options.onResizeColumn&&e.options.onResizeColumn.call(this,a,d)},onLoadSuccess:function(a){t(this),e.options.onLoadSuccess&&e.options.onLoadSuccess.call(this,a)}}))}(this,a)})},getSelfGrid:function(t){var a=t.closest(".datagrid");return a.length?a.find(">.datagrid-wrap>.datagrid-view>.datagrid-f"):null},getParentGrid:function(t){var a=t.closest("div.datagrid-row-detail");return a.length?a.closest(".datagrid-view").children(".datagrid-f"):null},getParentRowIndex:function(t){var a=t.closest("div.datagrid-row-detail");if(a.length){var d=a.closest("tr").prev();return parseInt(d.attr("datagrid-row-index"))}return-1}});