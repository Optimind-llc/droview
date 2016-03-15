export function sortLogs(logs, page, orderBy, asc) {
  const begin = ((page - 1) * 3);
  const end = begin + 3;

  if(asc) {
    return logs.slice().sort(function(a,b){
      if(a.orderBy < b.orderBy) return 1;
      if(a.orderBy > b.orderBy) return -1;
      return 0;
    }).slice(begin, end);
  } else {
    return logs.slice().sort(function(a,b){
      if(a.orderBy < b.orderBy) return -1;
      if(a.orderBy > b.orderBy) return 1;
      return 0;
    }).slice(begin, end);
  }
}

export function filterLogs(logs, method) {
  return　logs.filter(l => method.indexOf(l) > 0)
}
