using Microsoft.Owin;
using Owin;

[assembly: OwinStartupAttribute(typeof(AspEducationProject.Startup))]
namespace AspEducationProject
{
    public partial class Startup
    {
        public void Configuration(IAppBuilder app)
        {
            ConfigureAuth(app);
        }
    }
}
