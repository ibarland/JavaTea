public class TimesThree {

  public static double timesThree( double x ) {
    return 3*x;
    }

  public static double timesThree_bug_A( double x ) {
    return 0;
    }

  public static double timesThree_bug_B( double x ) {
    return Math.abs(3*x);
    }

  public static double timesThree_bug_C( double x ) {
    if (x > 0) {
      return x+x+x;
      }
    else if (x < 0) {
      return x-x-x;
      }
    return 17;  // Never reach here.
    }

  public static double timesThree_bug_D( double x ) {
    if (x > 0) {
      return x+x+x;
      }
    else if (x < 0) {
      return -1*(-x-x-x);
      }
    return 17;  // Never reach here.
    }

  }
