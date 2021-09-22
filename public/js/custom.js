function confirm(){
    event.preventDefault(); // prevent form submit
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this imaginary file!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
            form.submit()
            swal("Poof! Your imaginary file has been deleted!", {
                icon: "success",
            });
        } else {
          swal("Your imaginary file is safe!");
        }
      });
}