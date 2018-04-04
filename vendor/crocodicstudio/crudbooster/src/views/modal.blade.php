<!-- Modal -->
<div id="modalApp">
  <div id="modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">@{{ title }}</h4>
        </div>
        <div class="modal-body">
            <div :is="componentName" v-bind="props"></div>
        </div>
      </div>
    </div>
  </div>
</div>
