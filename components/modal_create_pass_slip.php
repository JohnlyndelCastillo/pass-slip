<!-- Modal Overlay -->
<div class="modal-overlay" id="createSlipModal">
  <div class="modal">

    <!-- Modal Header -->
    <div class="modal-header">
      <h2>Create new pass slip</h2>
      <button class="modal-close" onclick="closeModal()">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <div class="modal-divider"></div>

    <!-- Modal Body -->
    <div class="modal-body">
      <form action="/auth/create_slip.php" method="POST">

        <!-- Category -->
        <div class="form-group">
          <label class="form-label">Select Category</label>
          <div class="radio-group">
            <label class="radio-item">
              <input type="radio" name="category" value="individual" checked>
              Individual
            </label>
            <label class="radio-item">
              <input type="radio" name="category" value="section">
              Section
            </label>
          </div>
        </div>

        <!-- Requesting Student & Section -->
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Requesting Student</label>
            <input type="text" name="requesting_student" class="form-input" placeholder="Enter Full Name">
          </div>
          <div class="form-group">
            <label class="form-label">Section</label>
            <select name="section" class="form-select">
              <option value="" disabled selected>Enter Section</option>
              <option>32-A</option>
              <option>32-B</option>
              <option>32-C</option>
              <option>32-D</option>
              <option>32-E</option>
              <option>32-F</option>
              <option>32-G</option>
              <option>32-H</option>
              <option>32-I</option>
            </select>
          </div>
        </div>

        <!-- Request Date, Time, Purpose -->
        <div class="form-row">
          <div class="form-group form-group-sm">
            <label class="form-label">Request Date</label>
            <input type="date" name="request_date" class="form-input">
          </div>
          <div class="form-group form-group-sm-1"><!-- For spacing between date and time inputs -->
            <label class="form-label">Time</label>
            <input type="time" name="request_time" class="form-input">
          </div>
          <div class="form-group">
            <label class="form-label">Purpose</label>
            <input type="text" name="purpose" class="form-input" placeholder="Enter Purpose">
          </div>
        </div>

        <!-- Class Adviser & Technology Head -->
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Class Adviser</label>
            <select name="class_adviser" class="form-select">
              <option value="" disabled selected>Select Class Adviser</option>
              <option>Mr. Manatad</option>
              <option>Mr. Loquinario</option>
              <option>Mr. Bonghanoy</option>
              <option>Mr. Larobis</option>
              <option>Mr. Grondiano</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Technology Head</label>
            <select name="technology_head" class="form-select">
              <option value="" disabled selected>Select Technology Head</option>
              <option>Mr. Paredes</option>
              <option>Mr. Lauria</option>
              <option>Mr. Bonghanoy</option>
              <option>Mr. Go</option>
            </select>
          </div>
        </div>

        <!-- Note -->
        <div class="form-group">
          <label class="form-label">Note</label>
          <textarea name="note" class="form-textarea" rows="4"></textarea>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
          <button type="submit" class="btn-create">Create</button>
        </div>

      </form>
    </div>
  </div>
</div>