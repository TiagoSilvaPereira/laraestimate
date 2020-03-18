window.treatAxiosError = function(error) {
    if(error.response) {
        let data = error.response.data;
        let errors = data.errors;

        if(errors) {
            for(let key in errors) {
                toast.error(errors[key][0]);
            }
        } else {
            toast.error('Something went wrong');
        }
    }
}