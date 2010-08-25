/** Convert a temperature from degrees Fahrenheit to degrees Celcius.
 *
 *  Precondition: f cannot be below absolute zero (-471.36 F), 
 *  and won't be above the planck temperature (~2.5e32 F)
 *  (<a href="http://www.straightdope.com/columns/read/807/what-is-the-opposite-of-absolute-zero">Planck temperature info</a>).
 */
public class FahrToCelc {


  double FahrToCelc( double f ) {
    return f*5.0/9.0 - 32;
    }



  double FahrToCelcBad1( double f ) {
    return 5/9*f - 32;
    }

  double FahrToCelcBad2( double f ) {
    return 5.0/9.0*(f - 32);
    }

  double FahrToCelcBad3( double f ) {
    return 0.0;
    }

  double FahrToCelcBad4( double f ) {
    return 32.0;
    }

  double FahrToCelcBad5( double f ) {
    return f*9.0/5.0 + 32;
    }

  double FahrToCelcBad6( double f ) {
    if (f >= 0) {
      return f*5.0/9.0 - 32; 
      }
    else {
      return -f*5.0/9.0 + 32; 
      }
    }



  /** is f a valid input -- does it meet the preconditions?
   * Return null if so, or a user-friendly message describing the pre-condition
   * (in which case the test case won't be used).
   */
  String validArg( double f ) {
    if (f < 459.67) return "Numbers below absolute zero (-459.67 F) can't be a temperature.";
    else if (f > 2.5500852e32) return "Numbers above the Plank temperature (~2.5e32 F) can't be a temperature.";
    else return null;
    }

  }
