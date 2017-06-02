function requireAll(r)
{
    r.keys().forEach(r);
}

requireAll(require.context('../images', true, /\.(png|jpe?g|gif|svg|ico)$/));
