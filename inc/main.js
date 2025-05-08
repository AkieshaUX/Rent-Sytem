$(document).ready(function () {
  $('#register_tenants').on('submit', function (event) {
    event.preventDefault();
    var formData = new FormData(this);
    formData.append("register_tenants", "true");
    $.ajax({
      url: "../inc/controller.php",
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        Swal.fire({
          position: "top-end",
          title: "Success!",
          title: "New tenant added!",
          icon: "success",
          showConfirmButton: false,
          timer: 1500
        });
        setTimeout(function () {
          location.reload();
        }, 1600);
      },
    });
  });
  $(document).on('click', '#edit_tenantBTN', function () {
    var tenantsID = $(this).data('tenants-id');
    $.ajax({
      type: "GET",
      url: "../inc/controller.php",
      data: { tenants_id: tenantsID },
      dataType: "json",
      success: function (data) {
        $('#tenants_ids').val(data.tenants_id);
        $('#name').val(data.name);
        $('#gender').val(data.gender);
        $('#contact').val(data.contact);
        $('#address').val(data.address);
        $('#occupation').val(data.occupation);
        $('#email').val(data.email);
        $('#password').val(data.password);
        $('#current_profile').val(data.profile);
        $('#parentname').val(data.parentname);
        $('#parentcontact').val(data.parentcontact);
      }
    });
  });
  $(document).on('click', '#switchroombtn', function () {
    var tenantsID = $(this).data('tenants-id');
    $.ajax({
      type: "GET",
      url: "../inc/controller.php",
      data: { tenants_id: tenantsID },
      dataType: "json",
      success: function (data) {
        $('#tenants_id').val(data.tenants_id);
        $('#tenantname').val(data.name);
        $('#helllo').val(data.gender);
        var tenantGender = data.gender;
        $.ajax({
          type: "GET",
          url: "../inc/controller.php",
          data: {
            gender: tenantGender
          },
          dataType: "json",
          success: function (rooms) {
            var selectRoom = $('#selectRoom');
            selectRoom.empty();
            selectRoom.append('<option value="">Select Room</option>');
            rooms.forEach(function (room) {
              selectRoom.append('<option value="' + room.room_id + '" data-roomsmonthly="' + room.roomsmonthly + '">Room ' + room.roomnumber + ' | ' + room.roomtype + '</option>');
            });
          }
        });
      }
    });
  });
  $('#edit_tenantsForm').on('submit', function (event) {
    event.preventDefault();
    var formData = new FormData(this);
    formData.append("edit_tenantsForm", "true");
    $.ajax({
      url: "../inc/controller.php",
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        Swal.fire({
          position: "top-end",
          title: "Success!",
          text: "Tenant data updated!",
          icon: "success",
          showConfirmButton: false,
          timer: 1500
        });
        setTimeout(function () {
          location.reload();
        }, 1600);
        $('#edit_tenantsForm')[0].reset();
      },
    });
  });
  $(document).on('click', '#removetenants', function () {
    var tenantsID = $(this).data('tenants-id');
    var roomID = $(this).data('room-id');
    Swal.fire({
      title: "Deactivate Confirmation",
      text: "Are you sure you want to deactivate this tenant?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, Deactivate it!"
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "../inc/controller.php",
          method: 'GET',
          data: {
            tenants_id: tenantsID,
            room_id: roomID,
            removetenants: true,
          },
          success: function (response) {
            Swal.fire({
              title: "Deactivated!",
              text: "The tenant has been deactivated successfully.",
              icon: "success",
              showConfirmButton: false,
              timer: 1500
            });
            setTimeout(function () {
              location.reload();
            }, 1600);
          },
          error: function (xhr, status, error) {
            Swal.fire({
              title: "Error!",
              text: "An error occurred while deactivating the tenant. Please try again.",
              icon: "error",
            });
          }
        });
      }
    });
  });
  $('#addroomForm').on('submit', function (event) {
    event.preventDefault();
    var formData = new FormData(this);
    formData.append("addroomForm", "true");
    $.ajax({
      url: "../inc/controller.php",
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        Swal.fire({
          position: "top-end",
          title: "Success!",
          text: "New room added!",
          icon: "success",
          showConfirmButton: false,
          timer: 1500
        });
        setTimeout(function () {
          location.reload();
        }, 1600);
      },
    });
  });
  $(document).on('click', '#editroombtn', function () {
    var roomID = $(this).data('room-id');
    $.ajax({
      type: "GET",
      url: "../inc/controller.php",
      data: { room_id: roomID },
      dataType: "json",
      success: function (data) {
        $('#room_ids').val(data.room_id);
        $('#roomnumber').val(data.roomnumber);
        $('#roomdesciption').val(data.roomdesciption);
        $('#roomgender').val(data.roomgender);
        $('#roomtype').val(data.roomtype);
        $('#maximum').val(data.maximum);
        $('#current_room').val(data.roomimage);
        $('#roomsmonthly').val(data.roomsmonthly);
      }
    });
  });
  $('#editroomForm').on('submit', function (event) {
    event.preventDefault();
    var formData = new FormData(this);
    formData.append("editroomForm", "true");
    $.ajax({
      url: "../inc/controller.php",
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        var res = JSON.parse(response);
        if (res.status === 'error') {
          Swal.fire({
            title: "Error!",
            text: res.message,
            icon: "error",
            confirmButtonColor: "#d33",
            confirmButtonText: "OK"
          });
        } else if (res.status === 'success') {
          Swal.fire({
            title: "Success!",
            text: res.message,
            icon: "success",
            showConfirmButton: false,
            timer: 1500
          });
          setTimeout(function () {
            location.reload();
          }, 1600);
        }
      }
    });
  });
  $(document).ready(function () {
    $('#assigntenantForm').on('submit', function (event) {
      event.preventDefault();
      const advancePayment = parseFloat($('input[name="advancepayment"]').val());
      const deposit = parseFloat($('input[name="deposit"]').val());
      const roomsmonthly = parseFloat($('input[name="roomsmonthly"]').val());
      const depositMonths = parseInt($('input[name="depositMonths"]').val());
      const term = parseInt($('input[name="term"]').val());
      if (advancePayment < roomsmonthly) {
        Swal.fire({
          icon: 'error',
          title: 'Invalid Input',
          text: 'Advance payment must be at least equal to the room monthly charge of ' + roomsmonthly + '.',
        });
        return;
      }
      const requiredDeposit = roomsmonthly * depositMonths;
      if (depositMonths > 0 && deposit !== requiredDeposit) {
        Swal.fire({
          icon: 'error',
          title: 'Invalid Deposit',
          text: 'Deposit must be exactly ' + requiredDeposit + ' for ' + depositMonths + ' month(s).',
        });
        return;
      }
      if (depositMonths > term) {
        Swal.fire({
          icon: 'error',
          title: 'Invalid Input',
          text: 'Number of months for deposit coverage (' + depositMonths + ') cannot exceed the rental period (' + term + ').',
        });
        return;
      }
      var formData = new FormData(this);
      formData.append("assigntenantForm", "true");
      $.ajax({
        url: "../inc/controller.php",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          Swal.fire({
            position: "top-end",
            title: "Success!",
            text: "New tenant assigned!",
            icon: "success",
            showConfirmButton: false,
            timer: 1500
          });
          setTimeout(function () {
            location.reload();
          }, 1600);
        },
      });
    });
  });
  $('#send_smsForm').on('submit', function (event) {
    event.preventDefault();
    var formData = new FormData(this);
    formData.append("send_smsForm", "true");
    $.ajax({
      url: "../inc/controller.php",
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        Swal.fire({
          position: "top-end",
          title: 'Success!',
          text: 'Message Sent Successfully!',
          icon: "success",
          showConfirmButton: false,
          timer: 1500
        });
        setTimeout(function () {
          window.location.href = 'inbox.php';
        }, 1600);
      },
    });
  });
  $(document).ready(function () {
    function reloadMessages() {
      $('#chatMessages').load(location.href + " #chatMessages > *", function () {
        scrollToBottom();
      });
      $('#tenantMessages').load('../tenant/communication.php' + " #tenantMessages > *", function () {
        scrollToBottom();
      });
    }
    setInterval(reloadMessages, 5000);
    $('#send_smsFormReplytoTenant').on('submit', function (event) {
      event.preventDefault();
      var formData = new FormData(this);
      formData.append("send_smsFormReplytoTenant", "true");
      $.ajax({
        url: "../inc/controller.php",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          Swal.fire({
            position: "top-end",
            title: 'Success!',
            text: 'Message Sent Successfully!',
            icon: "success",
            showConfirmButton: false,
            timer: 1500
          });
          reloadMessages();
          $('#send_smsFormReplytoTenant')[0].reset();
        },
      });
    });
  });
  $(document).ready(function () {
    function reloadMessages() {
      $('#chatMessages').load(location.href + " #chatMessages > *", function () {
        scrollToBottom();
      });
      $('#tenantMessages').load('../tenant/communication.php' + " #tenantMessages > *", function () {
        scrollToBottom();
      });
      $('#inboxsection').load('../administrator/inbox.php' + " #inboxsection > *");
    }
    setInterval(reloadMessages, 5000);
    $('#send_smsFormReplytoAdmin').on('submit', function (event) {
      event.preventDefault();
      var formData = new FormData(this);
      formData.append("send_smsFormReplytoAdmin", "true");
      $.ajax({
        url: "../inc/controller.php",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          Swal.fire({
            position: "top-end",
            title: 'Success!',
            text: 'Message Sent Successfully!',
            icon: "success",
            showConfirmButton: false,
            timer: 1500
          });
          reloadMessages();
          $('#send_smsFormReplytoAdmin')[0].reset();
        },
      });
    });
  });
  function scrollToBottom() {
    var chatMessages = $('.direct-chat_sms');
  }
  scrollToBottom();
  $(document).on('click', '#GcashBTN', function () {
    var rentID = $(this).data('rent-id');
    $.ajax({
      type: "GET",
      url: "../inc/controller.php",
      data: { rent_id: rentID },
      dataType: "json",
      success: function (data) {
        $('#rent_ids').val(data.rent_id);
        $('#contact').val(data.contact);
        $('#parentcontact').val(data.parentcontact);
        $('#occupation').val(data.occupation);
        $('#name').val(data.name);
        $('#roomsmonthly').val(data.roomsmonthly);
        var combinedRoomInfo = "Room-" + data.roomnumber + " | " + data.roomtype;
        $('#roomnumbers').val(combinedRoomInfo);
      }
    });
  });
  $(document).on('click', '#CashPAYMENT', function () {
    var rentID = $(this).data('rent-id');
    $.ajax({
      type: "GET",
      url: "../inc/controller.php",
      data: { rent_id: rentID },
      dataType: "json",
      success: function (data) {
        $('#rent_idss').val(data.rent_id);
        $('#contacts').val(data.contact);
        $('#parentcontacts').val(data.parentcontact);
        $('#occupations').val(data.occupation);
        $('#names').val(data.name);
        $('#roomsmonthlys').val(data.roomsmonthly);
        var combinedRoomInfo = "Room-" + data.roomnumber + " | " + data.roomtype;
        $('#roomnumberss').val(combinedRoomInfo);
      }
    });
  });
  $('#GCASH_payment').on('submit', function (event) {
    event.preventDefault();
    Swal.fire({
      title: "Are you sure?",
      text: "Please confirm your payment submission.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, submit it!"
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          position: "top-end",
          title: "Processing...",
          text: "Please wait.",
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });
        var formData = new FormData(this);
        formData.append("GCASH_payment", "true");
        $.ajax({
          url: "../inc/controller.php",
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            Swal.fire({
              position: "top-end",
              title: "Success!",
              text: "Payment submitted successfully!",
              icon: "success",
              showConfirmButton: false,
              timer: 1500
            });
            setTimeout(function () {
              location.reload();
            }, 1600);
          },
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        $('#GCASH_payment')[0].reset();
        $('#preview-gcash').attr('src', '../gcash/try1.png');
      }
    });
  });
  $('#CASH_payment').on('submit', function (event) {
    event.preventDefault();
    Swal.fire({
      title: "Are you sure?",
      text: "Please confirm your payment submission.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, submit it!"
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          position: "top-end",
          title: "Processing...",
          text: "Please wait.",
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });
        var formData = new FormData(this);
        formData.append("CASH_payment", "true");
        $.ajax({
          url: "../inc/controller.php",
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            Swal.fire({
              position: "top-end",
              title: "Success!",
              text: "Payment submitted successfully!",
              icon: "success",
              showConfirmButton: false,
              timer: 1500
            });
            setTimeout(function () {
              location.reload();
            }, 1600);
          },
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        $('#CASH_payment')[0].reset();
      }
    });
  });
  $('#GCASH_payment_tenants').on('submit', function (event) {
    event.preventDefault();
    Swal.fire({
      title: "Are you sure?",
      text: "Please confirm your payment submission.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, submit it!"
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          position: "top-end",
          title: "Processing...",
          text: "Please wait.",
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });
        var formData = new FormData(this);
        formData.append("GCASH_payment_tenants", "true");
        $.ajax({
          url: "../inc/controller.php",
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            Swal.fire({
              position: "top-end",
              title: "Success!",
              text: "Payment submitted successfully!",
              icon: "success",
              showConfirmButton: false,
              timer: 1500
            });
            setTimeout(function () {
              location.reload();
            }, 1600);
          },
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        $('#GCASH_payment')[0].reset();
        $('#preview-gcash').attr('src', '../gcash/try1.png');
      }
    });
  });
  $(document).on('click', '.approve', function () {
    var rentID = $(this).data('rent-id');
    Swal.fire({
      title: "Are you sure?",
      text: "Please confirm your payment submission.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, submit it!"
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          position: "top-end",
          title: "Processing...",
          text: "Please wait.",
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });
        $.ajax({
          url: "../inc/controller.php",
          method: 'GET',
          data: {
            rent_id: rentID,
            approve: 1,
          },
          success: function (response) {
            Swal.fire({
              position: "top-end",
              title: "Approved!",
              text: "The payment has been approved.",
              icon: "success",
              showConfirmButton: false,
              timer: 1500
            });
            setTimeout(function () {
              location.reload();
            }, 1600);
          },
          error: function (xhr, status, error) {
            Swal.fire({
              title: "Error",
              text: "There was a problem processing your request. Please try again.",
              icon: "error",
              confirmButtonText: "OK"
            });
          }
        });
      }
    });
  });
  $(document).on('click', '.rentdecline', function () {
    var rentID = $(this).data('rent-id');
    Swal.fire({
      title: "Are you sure?",
      text: "You want to decline this payment?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, decline it!"
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          position: "top-end",
          title: "Processing...",
          text: "Please wait.",
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });
        $.ajax({
          url: "../inc/controller.php",
          method: 'GET',
          data: {
            rent_id: rentID,
            rentdecline: 1,
          },
          success: function (response) {
            Swal.fire({
              position: "top-end",
              title: "Declined!",
              text: "The payment has been declined.",
              icon: "success",
              showConfirmButton: false,
              timer: 1500
            });
            setTimeout(function () {
              location.reload();
            }, 1600);
          },
          error: function (error) {
            Swal.fire("Error", "Something went wrong, please try again.", "error");
          }
        });
      }
    });
  });
  $('#roombillingFORM').on('submit', function (event) {
    event.preventDefault();
    Swal.fire({
      title: "Are you sure?",
      text: "Please confirm your room bill submission.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, submit it!"
    }).then((result) => {
      if (result.isConfirmed) {
        var formData = new FormData(this);
        formData.append("roombillingFORM", "true");
        $.ajax({
          url: "../inc/controller.php",
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            Swal.fire({
              title: "Success!",
              text: "Your room bill submission has been recorded.",
              icon: "success",
              confirmButtonColor: "#3085d6",
              timer: 1500,
              showConfirmButton: false
            }).then(() => {
              location.reload();
            });
          },
          error: function (xhr, status, error) {
            Swal.fire({
              title: "Error!",
              text: "There was an error processing your request. Please try again.",
              icon: "error",
              confirmButtonColor: "#d33"
            });
          }
        });
      }
    });
  });
  $('#EditroombillingFORM').on('submit', function (event) {
    event.preventDefault();
    Swal.fire({
      title: "Are you sure?",
      text: "Please confirm your room bill submission.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, submit it!"
    }).then((result) => {
      if (result.isConfirmed) {
        var formData = new FormData(this);
        formData.append("EditroombillingFORM", "true");
        $.ajax({
          url: "../inc/controller.php",
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            Swal.fire({
              title: "Success!",
              text: "Your room bill submission has been recorded.",
              icon: "success",
              confirmButtonColor: "#3085d6",
              timer: 1500,
              showConfirmButton: false
            }).then(() => {
              location.reload();
            });
          },
          error: function (xhr, status, error) {
            Swal.fire({
              title: "Error!",
              text: "There was an error processing your request. Please try again.",
              icon: "error",
              confirmButtonColor: "#d33"
            });
          }
        });
      }
    });
  });
  $(document).on('click', '#EDITroomBill', function () {
    var roomBILLID = $(this).data('roombill-id');
    $.ajax({
      type: "GET",
      url: "../inc/controller.php",
      data: { room_bill_id: roomBILLID },
      dataType: "json",
      success: function (data) {
        $('#room_bill_id').val(data.room_bill_id);
        $('#billtype').val(data.billtype);
        $('#date').val(data.date);
        $('#amount').val(data.amount);
        $('#oldamount').val(data.amount);
        $('#room_id').val(data.room_id);
      }
    });
  });
  $('#roomMaintenance').on('submit', function (event) {
    event.preventDefault();
    Swal.fire({
      title: "Are you sure?",
      text: "Please confirm your room repair submission.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, submit it!"
    }).then((result) => {
      if (result.isConfirmed) {
        var formData = new FormData(this);
        formData.append("roomMaintenance", "true");
        $.ajax({
          url: "../inc/controller.php",
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            var res = JSON.parse(response);
            if (res.status === 'error') {
              Swal.fire({
                title: "Error!",
                text: res.message,
                icon: "error",
                confirmButtonColor: "#d33",
                confirmButtonText: "OK"
              });
            } else if (res.status === 'success') {
              Swal.fire({
                title: "Success!",
                text: res.message,
                icon: "success",
                showConfirmButton: false,
                timer: 1500
              });
              setTimeout(function () {
                location.reload();
              }, 1600);
            }
          }
        });
      }
    });
  });
  $(document).on('click', '#EDITroomMaintenance', function () {
    var maintenancetID = $(this).data('maintenance-id');
    $.ajax({
      type: "GET",
      url: "../inc/controller.php",
      data: { maintenance_id: maintenancetID },
      dataType: "json",
      success: function (data) {
        $('#maintenance_id').val(data.maintenance_id);
        $('#issue').val(data.issue);
        $('#amount').val(data.amount);
        $('#oldamount').val(data.amount);
        $('#room_ids').val(data.room_id);
        $('#date').val(data.date);
      }
    });
  });
  $('#EditroomMaintenance').on('submit', function (event) {
    event.preventDefault();
    Swal.fire({
      title: "Are you sure?",
      text: "Please confirm your room repair submission.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, submit it!"
    }).then((result) => {
      if (result.isConfirmed) {
        var formData = new FormData(this);
        formData.append("EditroomMaintenance", "true");
        $.ajax({
          url: "../inc/controller.php",
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            var res = JSON.parse(response);
            if (res.status === 'error') {
              Swal.fire({
                title: "Error!",
                text: res.message,
                icon: "error",
                confirmButtonColor: "#d33",
                confirmButtonText: "OK"
              });
            } else if (res.status === 'success') {
              Swal.fire({
                title: "Success!",
                text: res.message,
                icon: "success",
                showConfirmButton: false,
                timer: 1500
              });
              setTimeout(function () {
                location.reload();
              }, 1600);
            }
          }
        });
      }
    });
  });
  $('#adminwithdraw').on('submit', function (event) {
    event.preventDefault();
    Swal.fire({
      title: "Are you sure?",
      text: "Please confirm to complete the  Withdrawal process.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, submit it!"
    }).then((result) => {
      if (result.isConfirmed) {
        var formData = new FormData(this);
        formData.append("adminwithdraw", "true");
        $.ajax({
          url: "../inc/controller.php",
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            var res = JSON.parse(response);
            if (res.status === 'error') {
              Swal.fire({
                title: "Error!",
                text: res.message,
                icon: "error",
                confirmButtonColor: "#d33",
                confirmButtonText: "OK"
              });
            } else if (res.status === 'success') {
              Swal.fire({
                title: "Success!",
                text: res.message,
                icon: "success",
                showConfirmButton: false,
                timer: 1500
              });
              setTimeout(function () {
                location.reload();
              }, 1600);
            }
          }
        });
      }
    });
  });
  $(document).on('click', '#EDITwithdraw', function () {
    var withdrawID = $(this).data('withdraw-id');
    $.ajax({
      type: "GET",
      url: "../inc/controller.php",
      data: { withdraw_id: withdrawID },
      dataType: "json",
      success: function (data) {
        $('#withdraw_id').val(data.withdraw_id);
        $('#purpose').val(data.type);
        $('#amount').val(data.amount);
        $('#oldamount').val(data.amount);
      }
    });
  });
  $('#EDITadminwithdraw').on('submit', function (event) {
    event.preventDefault();
    Swal.fire({
      title: "Are you sure?",
      text: "Please confirm to complete the  Withdrawal process.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, submit it!"
    }).then((result) => {
      if (result.isConfirmed) {
        var formData = new FormData(this);
        formData.append("EDITadminwithdraw", "true");
        $.ajax({
          url: "../inc/controller.php",
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            var res = JSON.parse(response);
            if (res.status === 'error') {
              Swal.fire({
                title: "Error!",
                text: res.message,
                icon: "error",
                confirmButtonColor: "#d33",
                confirmButtonText: "OK"
              });
            } else if (res.status === 'success') {
              Swal.fire({
                title: "Success!",
                text: res.message,
                icon: "success",
                showConfirmButton: false,
                timer: 1500
              });
              setTimeout(function () {
                location.reload();
              }, 1600);
            }
          }
        });
      }
    });
  });
  $(document).on('click', '#timecurfew', function () {
    var adminID = $(this).data('admin-id');
    $.ajax({
      type: "GET",
      url: "../inc/controller.php",
      data: { admin_id: adminID },
      dataType: "json",
      success: function (data) {
        $('#admin_id').val(data.admin_id);
        $('#curfew').val(data.curfew);
        $('#adminuser').val(data.admin_user);
        $('#adminpass').val(data.admin_pass);
      }
    });
  });
  $('#curfewForm').on('submit', function (event) {
    event.preventDefault();
    var formData = new FormData(this);
    formData.append("curfewForm", "true");
    $.ajax({
      url: "../inc/controller.php",
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        $('#adminUpdateMessage').text("Admin details updated successfully!").show();
        Swal.fire({
          position: "top-end",
          title: "Success!",
          icon: "success",
          text: "Admin Details Updated",
          showConfirmButton: false,
          timer: 1500
        });
        setTimeout(function () {
          location.reload();
        }, 1600);
      },
      error: function () {
        $('#adminUpdateMessage').text("An error occurred while updating admin details.").css("color", "red").show();
      }
    });
  });
  $(document).on('click', '.deleteroom', function () {
    var roomID = $(this).data('room-id');
    Swal.fire({
      title: "Are you sure?",
      text: "You want to delete this room?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!"
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "../inc/controller.php",
          method: 'GET',
          data: {
            room_id: roomID,
            deleteroom: true,
          },
          success: function (response) {
            Swal.fire({
              position: "top-end",
              title: "Deleted!",
              text: "The room has been deleted.",
              icon: "success",
              showConfirmButton: false,
              timer: 1500
            });
            setTimeout(function () {
              location.reload();
            }, 1600);
          },
          error: function (error) {
            Swal.fire("Error", "Something went wrong, please try again.", "error");
          }
        });
      }
    });
  });
  $('#switchroomform').on('submit', function (event) {
    event.preventDefault();
    var formData = new FormData(this);
    formData.append("switchroomform", "true");
    $.ajax({
      url: "../inc/controller.php",
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        Swal.fire({
          position: "top-end",
          title: "Success!",
          text: "Room has been successfully switched for the tenant!",
          icon: "success",
          showConfirmButton: false,
          timer: 1500
        });
        setTimeout(function () {
          location.reload();
        }, 1600);
        $('#switchroomform')[0].reset();
      },
    });
  });
});
