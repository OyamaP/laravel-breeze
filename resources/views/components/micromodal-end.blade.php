{{--
    for MicroModal Bug
    If you use this method [ MicroModal.close(); ] and modals.
    When opening the last modal, open the previous modal.
    Therefore, insert a fake modal at the end.
--}}
<div class="hidden" id="modal-end-{{ $name }}"></div>
<div class="hidden" data-micromodal-trigger="modal-end-{{ $name }}">></div>
