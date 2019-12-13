export default {
    isAuthenticated: state => !!state.token,
    user: state => state.user,
    userId: state => state.user.id,
    authStatus: state => state.status,
    ads: state => state.ads
};
