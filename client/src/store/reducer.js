const reducer =(state,action) =>{
    switch (action.type){
        case "INC":
            return Object.assign({}, state, {
                token:state.token+action.payload
            });

    }
    return state;
};
export default reducer;