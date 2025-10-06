<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Complaint Portal — Software & Hardware</title>
  <style>
    :root{--bg:#f4f6f9;--card:#ffffff;--accent:#2b6cb0;--muted:#6b7280}
    *{box-sizing:border-box}
    body{
      font-family:Inter, system-ui, Arial, sans-serif;background:var(--bg);margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px
    }
    .card
    {background:var(--card);width:100%;max-width:900px;border-radius:12px;padding:20px;box-shadow:0 8px 30px rgba(17,24,39,0.06)
    }
    h1{
      margin:0 0 8px;font-size:20px
    }
    p.lead{
      margin:0 0 18px;color:var(--muted)
    }
    form .grid{
       display:grid;grid-template-columns:1fr 1fr;gap:12px
    }
    label{
     display:block;font-size:13px;margin-bottom:6px;color:#111827
    }
    .field
    { margin-bottom:12px
    }
    input[type=text],input[type=email],input[type=tel],select,textarea,input[type=file]{width:100%;padding:10px;border:1px solid #e6e9ef;border-radius:8px;font-size:14px}
    textarea{min-height:100px;resize:vertical}
    .full{grid-column:1 / -1}
    .radio-group{display:flex;gap:12px}
    .actions{display:flex;justify-content:flex-end;gap:10px;margin-top:12px}
    button{padding:10px 14px;border-radius:8px;border:0;font-weight:600;cursor:pointer}
    .btn-primary{background:var(--accent);color:white}
    .btn-ghost{background:transparent;border:1px solid #e6e9ef}
    @media (max-width:720px){.card{padding:16px}.grid{grid-template-columns:1fr}.actions{flex-direction:column-reverse;align-items:stretch}}
  </style>
</head>
<body>
  <main class="card">
    <h1>Complaint Portal</h1>
    <p class="lead">Choose complaint type and fill details.</p>

    <form action="submit_complaint.php" method="POST" enctype="multipart/form-data">
      <div class="grid">
        <!-- Complaint Type -->
        <div class="field full">
          <label>Complaint Type</label>
          <div class="radio-group">
            <label><input type="radio" name="type" value="software" checked> Software</label>
            <label><input type="radio" name="type" value="hardware"> Hardware</label>
            <label><input type="radio" name="type" value="other"> Other</label>
          </div>
        </div>

        <!-- Priority -->
        <div class="field">
          <label>Priority</label>
          <select name="priority" required>
            <option value="">Select priority</option>
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
            <option value="critical">Critical</option>
          </select>
        </div>

        <!-- Full Name -->
        <div class="field">
          <label>Full Name</label>
          <input type="text" name="fullname" placeholder="Your name" required>
        </div>

         <!-- Email -->
        <div class="field">
          <label>Email</label>
          <input type="email" name="email" placeholder="you@example.com" required>
        </div>

        <!-- Contact Number -->
        <div class="field">
          <label>Contact Number</label>
          <input type="tel" name="contact" placeholder="e.g. 9876543210" pattern="^[6-9]{1}[0-9]{9}" maxlength="10" required>
        </div>

        <!-- Software Section -->
        <div class="software-field field full">
          <label>Operating System</label>
          <input type="text" name="os" placeholder="Windows / macOS / Linux">
        </div>
        <div class="software-field field full">
          <label>Software Name & Version</label>
          <input type="text" name="software" placeholder="App name and version">
        </div>
        <div class="software-field field full">
          <label>Error Message / Steps to Reproduce</label>
          <textarea name="error_details"></textarea>
        </div>

        <!-- Hardware Section -->
        <div class="hardware-field field full">
          <label>Device Type</label>
          <select name="device_type">
            <option value="">Select</option>
            <option>Desktop</option>
            <option>Laptop</option>
            <option>Monitor</option>
            <option>Printer</option>
            <option>Other</option>
          </select>
        </div>
        <div class="hardware-field field full">
          <label>Model / Serial No.</label>
          <input type="text" name="serial">
        </div>
        <div class="hardware-field field full">
          <label>Symptoms / What you observed</label>
          <textarea name="hw_details"></textarea>
        </div>

        <!-- Common -->
        <div class="field full">
          <label>Attach files</label>
          <input type="file" name="attachments[]" multiple>
        </div>
        <div class="field full">
          <label>Location / Department</label>
          <input type="text" name="location">
        </div>
        <div class="field full">
          <label>Additional comments</label>
          <textarea name="comments"></textarea>
        </div>
        <div class="field full">
          <label>Status</label>
          <select name="status">
            <option>New</option>
            <option>In Progress</option>
            <option>On Hold</option>
            <option>Resolved</option>
          </select>
        </div>
      </div>

      <div class="actions">
        <button type="reset" class="btn-ghost">Reset</button>
        <button type="submit" class="btn-primary">Submit Complaint</button>
      </div>
    </form>
  </main>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const radios = document.querySelectorAll("input[name=type]");
      const softwareFields = document.querySelectorAll(".software-field");
      const hardwareFields = document.querySelectorAll(".hardware-field");

      function toggleFields() {
        const selected = document.querySelector("input[name=type]:checked").value;
        softwareFields.forEach(el => el.style.display = (selected === "software") ? "block" : "none");
        hardwareFields.forEach(el => el.style.display = (selected === "hardware") ? "block" : "none");
      }

      radios.forEach(radio => radio.addEventListener("change", toggleFields));
      toggleFields(); // run on page load
    });
  </script>
</body>
</html>