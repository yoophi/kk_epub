fetchSpine = ->
  $.ajax
    type: "GET"
    url: "#{BASE_URL}my/books/#{BOOK_ID}/spine.json"
    dataType: "json"
    success: (r) ->
      compiled = _.template("""
                            <tr class='spine-row'>
                              <td class='spine-id'><%= BookSpine.id %></td>
                              <td class='article-id'><%= Article.id %></td>
                              <td><%= Article.subject %></td>
                              <td><%= Article.created %></td>
                              <td><%= BookSpine.order %></td>
                              <td><a href='' class='btn-mini delete'>delete</a></td>
                            </tr>
                            """)
      $.each r, (index, value) ->
        html = compiled(value)
        $("#sort3").find("tbody").append html

      $("#sort3 .delete").click ->
        $elRow = $(this).parents('tr.spine-row');
        spine_id = $elRow.find('.spine-id').html()

        $.ajax
          type: "DELETE"
          url: "#{BASE_URL}my/books/#{BOOK_ID}/spine/#{spine_id}.json" 
          dataType: "json"
          success: (r) ->
            $elRow.remove()

        return false

getArticleIds = ->
  article_ids = []
  $("#sort3").find(".spine-row").each (index) ->
    article_ids.push  $(this).find(".article-id").html()

  return article_ids


class SortableHandler
  initialized: false

  constructor: (@el, @helper)->

  fixHelper: (e, ui) ->
    ui.children().each ->
      $(this).width $(this).width()

    ui

  disable: ->
    $(@el).sortable("disable")
    @hideButtons()

  enable: ->
    unless (@initialized)
      $(@el).sortable(
        helper: @fixHelper
      ).disableSelection()
      @initialized = true
    else
      $(@el).sortable("enable")

    @showButtons()

  showButtons: ->
    $('.ctl-btn').show()

  hideButtons: ->
    $('.ctl-btn').hide()

  applySort: ->
    sort_data = []
    spine_order = 1
    $(@el).find('.spine-row').each (index) ->
      sort_data.push 
        id: $(this).find('.spine-id').html()
        article_id: $(this).find('.article-id').html()
        order: spine_order++

    $.ajax
      type: "POST"
      url: "#{BASE_URL}my/books/#{BOOK_ID}/spine_order_update.json"
      data: JSON.stringify sort_data
      dataType: "json"
      success: (r) ->

$(document).ready ->
  fetchSpine()

  handler = new SortableHandler '#sort3 tbody'

  $('#btn-enable-sortable').click -> handler.enable()

  $('#btn-apply-sort').click -> 
    handler.applySort()
    handler.disable()

  $('#btn-cancel-sort').click -> 
    $("#sort3").find("tbody").html ""
    fetchSpine()
    handler.disable()

  $("#btn1").click ->
    $("#myModal").modal keyboard: false
    $("#article_list").find("tbody").html ""
    $.ajax
      type: "GET"
      url: BASE_URL + "my/articles.json"
      dataType: "json"
      success: (r) ->
        compiled1 = _.template("""
                              <tr>
                                <td><input type='checkbox' data-spine-id='<%= id %>' /></td>
                                <td><%= id %></td>
                                <td><%= category_id %></td>
                                <td><%= subject %></td>
                                <td><%= created %></td>
                              </tr>"
                              """)
        compiled2 = _.template("""
                              <tr>
                                <td><input type='checkbox' data-spine-id='<%= id %>' disabled /></td>
                                <td><%= id %></td>
                                <td><%= category_id %></td>
                                <td><%= subject %></td>
                                <td><%= created %></td>
                              </tr>"
                              """)
        article_ids = getArticleIds()
        $.each r, (index, value) ->

          if _.indexOf(article_ids, value.id) >= 0
            html = compiled2(value)
          else 
            html = compiled1(value)

          $("#article_list tbody").append html

  $("#btn-modal-close").click ->
    $("#myModal").modal "hide"

  $("#btn-spine-add").click ->
    article_ids = []
    $("#article_list").find(":checked").each (index) ->
      article_ids.push $(this).attr('data-spine-id')

    $.ajax
      type: "POST"
      url: "#{BASE_URL}my/books/#{BOOK_ID}/spine.json"
      data: JSON.stringify( article_ids: article_ids )
      dataType: "json"
      success: (r) ->
        $("#sort3").find("tbody").html ""
        fetchSpine()
        $("#myModal").modal "hide"
