using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Web;
using System.Web.Mvc;
using AspEducationProject.Models;

namespace AspEducationProject.Controllers {
   public class HomeController : Controller {
      // GET: Home
      public ActionResult Index() {
         ViewBag.Greeting = "Hello!";
         return View();
      }

      [HttpGet] //отработает когда мы перешли нас страничку /Home/RsvpForm впервые
      public ActionResult RsvpForm() {
         return View();
      }

      [HttpPost] //отработает тогда когда мы отарпавили методу RsvpForm данные
      public ActionResult RsvpForm(GuestResponse objGuestResponse) {
         return View("Thanks", objGuestResponse);
      }
   }
}