class AgeCalculator {
  static Map<String, int> calculate(DateTime birthDate) {
    final now = DateTime.now();

    int years = now.year - birthDate.year;
    int months = now.month - birthDate.month;
    int days = now.day - birthDate.day;

    if (days < 0) {
      months -= 1;
      final prevMonth = DateTime(now.year, now.month, 0);
      days += prevMonth.day;
    }

    if (months < 0) {
      years -= 1;
      months += 12;
    }

    final totalDays = now.difference(birthDate).inDays;
    final hours = totalDays * 24;
    final minutes = hours * 60;

    return {
      "years": years,
      "months": months,
      "days": days,
      "hours": hours,
      "minutes": minutes,
    };
  }
}